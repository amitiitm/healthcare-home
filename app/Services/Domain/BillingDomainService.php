<?php

namespace App\Services\Domain;
use GuzzleHttp\Client;
use App\Models\ORM\PayumoneyTransaction;
use App\Contracts\Domain\IUserDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\AgeRange;
use App\Models\ORM\PriceUnit;
use App\Models\ORM\Complaint;
use App\Models\ORM\Condition;
use App\Models\ORM\CustomerFeedback;
use App\Models\ORM\CustomerNotification;
use App\Models\ORM\CustomerStatusRequest;
use App\Models\ORM\CustomerVendorAttendance;
use App\Models\ORM\CustomerVendorStatus;
use App\Models\ORM\OperationalStatus;
use App\Models\ORM\PaymentType;
use App\Models\ORM\LeadStatus;
use App\Models\ORM\Enquiry;
use App\Models\ORM\Ailment;
use App\Models\ORM\Equipment;
use App\Models\ORM\FoodType;
use App\Models\ORM\Gender;
use App\Models\ORM\Language;
use App\Models\ORM\Lead;
use App\Models\ORM\Sequence;
use App\Models\ORM\PaymentPeriod;
use App\Models\ORM\Patient;
use App\Models\ORM\Religion;
use App\Models\ORM\Service;
use App\Models\ORM\Order;
use App\Models\ORM\Invoice;
use App\Models\ORM\Shift;
use App\Models\ORM\Task;
use App\Models\ORM\TaskCategory;
use App\Models\ORM\UserType;
use App\Models\ORM\UserVendor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mockery\CountValidator\Exception;
use App\Models\ORM\Export;
use Barryvdh\Snappy\Facades\SnappyPdf;
use DateTime;
use App\Models\Enums\PramatiConstants;

class BillingDomainService
{

    public function generateOrder($leadId, $userId){
      $orderDetails = Order::where('lead_id','=',$leadId)->where('order_status','=', 1)->first();
      
      if(!$orderDetails) {
        $leadOrm = Lead::where('id','=', $leadId)->first();
        $order = new Order();
        //$order->user_id = $leadOrm->user_id; Need to verify why user_id blank in lead
        $order->user_id = $userId;
        $order->lead_id = $leadId;
        $order->order_date = $leadOrm->start_date;
        $order->order_number = $this->generateInvoiceNumber($leadOrm, 'order', 'OR');
        $order->created_by = $userId;
        $order->order_status = 1; //Need to define constant
        $order->payment_type_id = $leadOrm->payment_type_id;
        $order->payment_mode_id = $leadOrm->payment_mode_id;
        $order->payment_period_id = $leadOrm->payment_period_id;
        $order->service_id = $leadOrm->service_id;
        $order->price = $leadOrm->prices->last()->price;
        $order->price_unit_id = $leadOrm->prices->last()->price_unit_id;
        if($order->save()) {
            $this->generateInvoice($leadOrm, $order, 1);
        }
      }
    }
    
    public function generateAutoInvoice($lead_id = 0) {
        if($lead_id == 0){
            $leads = Lead::all();
        }else{
         $leads = Lead::where('id','=',$lead_id)->get();   
        }
        $startStatus = OperationalStatus::where('slug','=', 'started')->first();

        if(!empty($leads)) {
          foreach($leads as $lead) { 
            $lead_status = LeadStatus::where('lead_id','=', $lead->id)->orderBy('id', 'desc')->first();
            $Order = Order::where('lead_id','=', $lead->id)->first(); 
            if(!empty($lead_status)) {
                if(($lead_status->status_id) == ($startStatus->id)) {
                    
                    $lastInvoice = Invoice::where('lead_id','=', $lead->id)->orderBy('id', 'desc')->first();
                    if(!empty($lastInvoice)) {
                        $paymentType = PaymentType::where('id','=', $lead->payment_type_id)->first();
                        if($paymentType->label == 'Prepaid') {
                          if(strtotime($lastInvoice->invoice_to_date) <= strtotime('today')) {
                            $this->generateInvoice($lead, $Order, 2);  
                          }  
                        } else {
                            $invoiceFrequency = PaymentPeriod::where('id','=', $lead->payment_period_id)->first();
                            $invoiceToDate = strtotime($lastInvoice->invoice_to_date);
                            $postInvoiceDate = strtotime("+".$invoiceFrequency->days." day", $invoiceToDate);
                            //$toDate = date('Y-m-d', $postInvoiceDate);
                            //echo date('Y-m-d', $postInvoiceDate);
                            //echo "dsfdf".date('Y-m-d', strtotime('today'));
                            
                            if($postInvoiceDate <= strtotime('today')) {
                              $this->generateInvoice($lead, $Order, 2);    
                            }
                        }
                    }    
                }
            }    
          }
        }    
    }
    
    public function getLastInvoiceForLead($leadOrm) {
       return Invoice::where('lead_id','=', $leadOrm->id)->orderBy('id', 'desc')->first();
    }
    
    public function generateInvoice($leadOrm, $order, $invoiceType) {
        //$invoiceType 1 for manual and 2 for auto; TODO: move to constant
        
        $invoiceORM = new Invoice();
        
        $invoiceAmount = $this->getInvoiceAmount($leadOrm, $invoiceType);
        $billingDays = $this->getBillingDays($leadOrm, $invoiceType);
        $invoiceNumber = $this->generateInvoiceNumber($leadOrm, 'invoice', 'IN');
       
        if($invoiceType == 1) {
          $fromDate = $leadOrm->start_date;
          $toDate = $this->getInvoiceToDate($leadOrm, 1);
        } else {
          $timestamp = strtotime($this->getLastInvoiceForLead($leadOrm)->invoice_to_date);
          $fromDate = strtotime("+1 day", $timestamp);
          $fromDate = date('Y-m-d', $fromDate);
          $toDate = $this->getInvoiceToDate($leadOrm, 2);  
        }
        if($leadOrm->paymentMode->label == 'Online') {
            $payu_parameter = ['customerEmail' => $leadOrm->email,
                               'customerPhone'=> $leadOrm->phone,
                               'customerName' => $leadOrm->customer_name,
                               'amount'=> $invoiceAmount,
                               'paymentDescription' => 'Payment for the Invoice '.$invoiceNumber,
                               'transactionId' => $invoiceNumber
                              ];
            $payuResponse = $this->getPayUMoneyPaymentUrl($payu_parameter);
            
            if($payuResponse['responseCode'] == 200) {
              $invoiceORM->payumoney_url = $payuResponse['responseMessage']['payment_url'];
            }
        }   
            
        $invoiceORM->order_id = $order->id;
        $invoiceORM->lead_id = $leadOrm->id;
        $invoiceORM->price_per_day = $leadOrm->prices->last()->price; 
        $invoiceORM->price_unit_id = $leadOrm->prices->last()->price_unit_id;
        $invoiceORM->payment_type_id = $leadOrm->payment_type_id;
        $invoiceORM->payment_mode_id = $leadOrm->payment_mode_id;
        $invoiceORM->payment_period_id = $leadOrm->payment_period_id;
        $invoiceORM->total_amount = $invoiceAmount;
        
        $invoiceORM->locality_id = $leadOrm->locality_id;
        $invoiceORM->city_id = 1;
        $invoiceORM->service_id = $leadOrm->service_id;
        $invoiceORM->customer_name = $leadOrm->customer_name.' '.$leadOrm->customer_last_name;
        $invoiceORM->invoice_type = $invoiceType; //Manual 1, auto 2: Need to define constant
        $invoiceORM->email = $leadOrm->email;
        $invoiceORM->address = $leadOrm->address;
        $invoiceORM->landmark = $leadOrm->landmark;
        $invoiceORM->phone = $leadOrm->phone;
        $invoiceORM->locality_id = $leadOrm->locality_id;
        
        $invoiceORM->service_start_date = $leadOrm->start_date;
        $invoiceORM->invoice_from_date = $fromDate;
        $invoiceORM->invoice_to_date = $toDate;
        $invoiceORM->invoice_number = $invoiceNumber;
        $invoiceORM->billing_days = $billingDays;
        
        if($invoiceType == 2) {
          $invoiceORM->status = PramatiConstants::PENDING_INVOICE_STATUS;
        } else {
          $invoiceORM->status = PramatiConstants::APPROVED_INVOICE_STATUS;
        }
        if($invoiceORM->save()) {
            if($invoiceType == 1) {
                if(!empty($leadOrm->email)) { 
                  $this->sendInvoiceEmail($leadOrm, $invoiceORM);
                }

                if(!empty($leadOrm->phone)) { 
                  $message = urlencode("Dear Customer, your invoice for the current period has been mail to you. Kindly make the payment in time to enjoy uninterrupted service. Thanks, Pramaticare ");  
                  $this->sendSMS($leadOrm->phone, $message);
                }
            }    
        }
    }
    
    public function editInvoicePaymentMode($leadOrm,$invoice){
       if($invoice->payment_mode_id == '3') {
            $payu_parameter = ['customerEmail' => $leadOrm->email,
                               'customerPhone'=> $leadOrm->phone,
                               'customerName' => $leadOrm->customer_name,
                               'amount'=> $invoice->total_amount,
                               'paymentDescription' => 'Payment for the Invoice '.$invoice->invoice_number,
                               'transactionId' => $invoice->invoice_number
                              ];
            $payuResponse = $this->getPayUMoneyPaymentUrl($payu_parameter);
            
            if($payuResponse['responseCode'] == 200) {
              $invoice->payumoney_url = $payuResponse['responseMessage']['payment_url'];
            }
        }  
        return $invoice;
    }
    
    public function sendInvoiceEmail($leadOrm, $invoiceORM) {
        //returing from here as mail not working on prod
        if($leadOrm->paymentMode->label == 'Online') {
          return true;
        }
        $mail = new \PHPMailer();
        //$mail->SMTPDebug = 3; // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = env('PHPMAILER_HOST');  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = env('PHPMAILER_USERNAME');                 // SMTP username
        $mail->Password = env('PHPMAILER_PASSWORD');                           // SMTP password
        $mail->SMTPSecure = 'none';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = env('PHPMAILER_PORT');                                    // TCP port to connect to

        $mail->setFrom(env('PHPMAILER_FROM_EMAIL'), env('PHPMAILER_FROM_NAME'));
        $mail->addAddress($leadOrm->email, $leadOrm->customer_name);     // Add a recipient
        $mail->addCC('nandkishore@pramaticare.com', 'Nand Kishore');
        $mail->addCC('amit.pandey.iitm@gmail.com', 'Amit');
        $mail->addCC('anupkumar@pramaticare.com', 'Anup');
        $mail->addCC('vishal@pramaticare.com','Vishal');
        $mail->addCC('nandkishore.jha23@gmail.com','Nand Kishore');
        $mail->addCC('anup.kumar48@gmail.com','Anup');
        $mail->addReplyTo(env('PHPMAILER_REPLY_EMAIL'), env('PHPMAILER_REPLY_NAME'));
        // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $from_date = date_format(new DateTime($invoiceORM->invoice_from_date), "d F, Y");
        $to_date = date_format(new DateTime($invoiceORM->invoice_to_date), "d F, Y");
        $mail->Subject = 'Pramati Care - Invoice for the period of '.$from_date.'-'.$to_date;
        $mail->Body = view('emails.invoice_mailer', ['lead' => $leadOrm, 'invoice' => $invoiceORM,
                           'from_date' => $from_date, 'to_date' => $to_date]);
        $pdf = \PDF::loadView('admin.reports.show_invoice',['invoice'=>$invoiceORM, 'lead' => $leadOrm]);
        $mail->AddStringAttachment($pdf->output(), $invoiceORM->invoice_number.".pdf");
        
        if(!$mail->send()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function sendPaymentCollectionEmail($user,$lead,$invoiceORM) {
        //returing from here as mail not working on prod
        //return true;
        $mail = new \PHPMailer();
        //$mail->SMTPDebug = 3; // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = env('PHPMAILER_HOST');  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = env('PHPMAILER_USERNAME');                 // SMTP username
        $mail->Password = env('PHPMAILER_PASSWORD');                           // SMTP password
        $mail->SMTPSecure = 'none';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = env('PHPMAILER_PORT');                                    // TCP port to connect to

        $mail->setFrom(env('PHPMAILER_FROM_EMAIL'), env('PHPMAILER_FROM_NAME'));
        $mail->addAddress($user->email, $user->name);     // Add a recipient
        $mail->addReplyTo(env('PHPMAILER_REPLY_EMAIL'), env('PHPMAILER_REPLY_NAME'));
        // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $date_of_collection = date_format(new DateTime($invoiceORM->date_of_collection), "d F, Y");
        $mail->Subject = 'Pramati Care - Payment Collection Notification for Invoice# '.$invoiceORM->invoice_number. ' - On Date : '.$date_of_collection;
        $mail->Body = view('emails.payment_collection_notifier_mailer', ['user' => $user, 'invoice' => $invoiceORM,'date_of_collection' => $date_of_collection,'lead' => $lead]);
        
        if(!$mail->send()) {
            return true;
        } else {
            return false;
        }
    }
    
    //we need to pass an array having user info like email,phone,name and order details like amount, invoice number etc...
    private function getPayUMoneyPaymentUrl($parameters){
        //$parameters = ["customerEmail"=>"amit.pandey.iitm@gmail.com","customerPhone"=>9650071356,"customerName"=>"Amit","amount"=>10,"paymentDescription"=>"mobile","transactionId"=>3456];
        $response = '';
        $customer_email = $parameters['customerEmail'];
        $customer_phone = $parameters['customerPhone'];
        $customer_name = $parameters['customerName'];
        $amount = $parameters['amount'];
        $payment_description = $parameters['paymentDescription'];
        $transaction_id = $parameters['transactionId'];
        if($customer_email != '' && $customer_phone != '' && $customer_name != '' && $amount > 0 && $transaction_id != ''){
            //$client = new Client(['headers' => ['Authorization' => 'oslKi6QVRVd2VTdapaAKJy4qyc/FqTIGkzR9HADwErQ=']]);
            $client = new Client(['headers' => ['Authorization' => 'navfflQRf6nJIPPR5euFA/W9Qalq71B+P9/lRwBHZ2k=']]);
            $res = $client->request('POST', env('PAYU_URL')."?customerEmail=".$customer_email."&customerPhone=".$customer_phone."&customerName=".$customer_name."&amount=".$amount."&paymentDescription=".$payment_description."&transactionId=".$transaction_id, []);
            $response_body = json_decode($res->getBody()->getContents());
            //echo $response_body->{'result'}->{'paymentURL'};
            if($response_body->{'status'} == 0){
                $transaction = new PayumoneyTransaction();
                $transaction->transaction_date = date('Y-m-d H:i:s');
                $transaction->invoice_number = $transaction_id;
                $transaction->status = (string)$response_body->{'status'};
                $transaction->message = $response_body->{'message'};
                $transaction->amount = $amount;
                $transaction->customer_name = $customer_name;
                $transaction->customer_email = $customer_email;
                $transaction->payment_url = $response_body->{'result'}->{'paymentURL'};
                $transaction->payment_id = $response_body->{'result'}->{'paymentId'};
                $transaction->email_sent = $response_body->{'result'}->{'emailSent'};
                $transaction->error_code = $response_body->{'errorCode'};
                $transaction->response_code = $response_body->{'responseCode'};
                $transaction->save();
                $response = ['responseCode' => 200,'responseMessage' => $transaction];
            }else{
             $response = ['responseCode' => 500,'responseMessage' => $response_body->{'result'}];     
            }
        }else{
          $response = ['responseCode' => 500,'responseMessage' => 'Some Parameters are missing please pass name,phone,email,mobile & invoice number in specific keys.'];  
        }
        return $response;
     }
     
    private function getInvoiceAmount($leadOrm, $invoiceType) {
        $price = $leadOrm->prices->last()->price;
        $price_unit_id = $leadOrm->prices->last()->price_unit_id;
        $paymentUnit = PriceUnit::where('id','=', $price_unit_id)->first();
        $invoiceFrequency = PaymentPeriod::where('id','=', $leadOrm->payment_period_id)->first();
        $billingDays =  $this->getBillingDays($leadOrm, $invoiceType)          ;
        $amount = $price;
        if($paymentUnit->label == 'Monthly') {
          if($invoiceType == 1) {  
            $numberOfDays = date("t");
            $amount = ($price * $billingDays) / $numberOfDays;
          } else {
            $amount = $price;
          }  
        } else {  
          $amount = $price * $billingDays;
        }  
          
        return $amount;
    }
    
    private function generateInvoiceNumber($lead, $type, $prefix) {
        $last_invoice = Sequence::where('sequence_type','=', $type)->orderBy('id', 'desc')->first();
        if(empty($last_invoice)) {
            $invoice_number = '00001';
            $sequence_number = 1;
        } else {
            $invoice_number = str_pad(($last_invoice->id + 1), 5, '0', STR_PAD_LEFT);
            $sequence_number = $last_invoice->id + 1;
        }
        $sequence = new Sequence();
        $sequence->squence_number = $sequence_number;
        $sequence->sequence_type = $type;
        $sequence->save();
        $fc = ucfirst(substr($lead->customer_name, 0, 1));
        $lc = ucfirst(substr($lead->customer_last_name, 0, 1));
        return $prefix.date('Y').date('m').$fc.$lc.$invoice_number;
    }
    
    private function getInvoiceToDate($lead, $invoiceType) {
        $invoiceFrequency = PaymentPeriod::where('id','=', $lead->payment_period_id)->first();
        if($invoiceType == 1) {
          $timestamp = strtotime($lead->start_date);    
        } else {
          $timestamp = strtotime($this->getLastInvoiceForLead($lead)->invoice_to_date);
        }
        
        if($invoiceFrequency->label == 'Monthly') {
              $toDate = date("Y-m-t");  
        } else {
           if($invoiceType == 1) {
              $days_remaining = (int)date('t', $timestamp) - (int)date('j', $timestamp); 
              if($days_remaining > $invoiceFrequency->days) {
                $toDate = strtotime("+".($invoiceFrequency->days -1)." day", $timestamp);
                $toDate = date('Y-m-d', $toDate);
              } else {
                $toDate = date("Y-m-t");
              }
           } else {
             $toDate = strtotime("+".$invoiceFrequency->days." day", $timestamp);
             $toDate = date('Y-m-d', $toDate);
           }  
        }
        return $toDate;    
    }
    
    public function getBillingDays($lead, $invoiceType) {
        $invoiceFrequency = PaymentPeriod::where('id','=', $lead->payment_period_id)->first();
        if($invoiceFrequency->label == 'Monthly') {
          $numberOfDays = date("t");
        } else {
          $numberOfDays =  $invoiceFrequency->days;    
        }
        return $numberOfDays;
        /*
        if($invoiceType == 1) {
          $timestamp = strtotime($lead->start_date);    
        } else {
          $timestamp = strtotime($this->getLastInvoiceForLead($lead)->invoice_to_date);
        }
        $days_remaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
        $billingDays = ($days_remaining > $numberOfDays) ? $numberOfDays : $days_remaining;
        return $billingDays;
         * 
         */
    }
    
    public function sendSMS($phone,$message,$type=null){
        //returing from here as sms not working on prod
        //return true;
        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$phone;
        $url.= '&msgtext='.$message;
        if($type){
            $url.= '&smstype='.$type;
        }else{
            $url.= '&smstype=13';
        }

        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;
        //echo "Url To Hit: ".$urlToUse;

        $ch = curl_init($urlToUse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        //echo $curl_scraped_page;
        curl_close($ch);
        return true;
    }
    
}
