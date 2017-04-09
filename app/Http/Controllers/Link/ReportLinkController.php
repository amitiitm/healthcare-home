<?php

namespace App\Http\Controllers\Link;

use App\Contracts\Domain\IVendorDomainContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Services\Domain\BillingDomainService;
use App\Http\Controllers\Controller;
use App\Services\Domain\OperationDomainService;
use App\Services\Rest\OperationRestService;
use App\Templates\PRResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use App\Models\ORM\Order;
use App\Models\ORM\PaymentPeriod;
use App\Models\ORM\Invoice;
use App\Models\ORM\LeadStatus;
use App\Models\ORM\OperationalStatus;
use App\Models\ORM\InvoicePaymentCollection;
use App\Models\ORM\Lead;
use App\Models\User;
use App\Models\ORM\Service;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use App\Models\Enums\PramatiConstants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use DateTime;

class ReportLinkController extends Controller {

    protected $vendorDomainService;

    public function __construct(IVendorDomainContract $IVendorDomainContract) {
        $this->vendorDomainService = $IVendorDomainContract;
    }

    public function activeProjectReport() {
        return view('admin.reports.activeproject');
    }

    public function vendorAttendanceReport() {
        return view('admin.reports.vendorattendance');
    }

    public function orders() {
        return view('admin.reports.orders');

        //return view('admin.reports.orders', array('orders' => $orders));
    }

    public function orderData() {

        $orders = Order::join('leads', 'leads.id', '=', 'orders.lead_id')
                ->join('patients', 'patients.lead_id', '=', 'leads.id')
                ->leftJoin('services', 'services.id', '=', 'orders.service_id')
                ->leftJoin('payment_units', 'payment_units.id', '=', 'orders.price_unit_id')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->leftJoin('payment_modes', 'payment_modes.id', '=', 'orders.payment_mode_id')
                ->leftJoin('payment_periods', 'payment_periods.id', '=', 'orders.payment_period_id')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'orders.payment_type_id')
                ->select('leads.customer_name', 'patients.name as patient_name', 'users.name as user_name', 'total_amount', 'remaining_amount', 'order_date', 'orders.id', 'order_number', 'leads.id as lead_id', 'leads.start_date as start_date', 'orders.created_at', 'payment_modes.label as pmode', 'payment_periods.label as period', 'payment_types.label as payment_type', 'services.name as service_name', 'payment_units.label as price_unit', 'orders.price'
                )
                ->orderBy('orders.created_at', 'DESC')
                ->get();
        return Datatables::of($orders)
                        ->editColumn('lead_name', function ($model) {
                            return '<a href="/lead/' . $model->id . '">' . $model->lead_name . '</a>';
                        })
                        ->addColumn('action_info', function ($model) {
                            return '<a href="/admin/invoices?order_id=' . $model->id . '">View | </a>'.'<a href="/admin/generate_invoice_manually/' . $model->id . '">Generate</a>';
                        })
                        ->make(true);
    }

    public function invoices() {
        $invoice_statuses = [PramatiConstants::PENDING_INVOICE_STATUS => 'Pending', PramatiConstants::APPROVED_INVOICE_STATUS => 'Approved'];
        return view('admin.reports.invoices');
    }

    public function invoiceData() {
        if (!empty($_GET['order_id'])) {
            $invoices = Invoice::join('orders', 'orders.id', '=', 'invoices.order_id')
                    ->where('order_id', '=', $_GET['order_id'])
                    ->select('orders.*', 'invoices.*')
                    ->get();
        } else {
            $invoices = Invoice::join('orders', 'orders.id', '=', 'invoices.order_id')
                    ->select('invoices.status', 'invoices.order_id', 'invoices.invoice_number', 'invoices.created_at', 'invoices.order_id', 'invoices.customer_name', 'invoices.email', 'invoices.phone', 'invoices.address', 'invoices.price_per_day', 'invoices.total_amount', 'invoices.id', 'orders.order_number', 'invoices.assigned_user_id', 'invoices.lead_id')
                    ->orderBy('invoices.created_at', 'DESC')
                    ->get();
        }

        return Datatables::of($invoices)
                        ->editColumn('invoice_number', function ($model) {              
                            return '<a href="/admin/edit_invoice/' . $model->id .'?referer=admin'.'" class="edit_link" data-value=' . $model->id . '>'.$model->invoice_number.'</a>';
                        })
                        ->editColumn('status', function ($model) {
                            if ($model->status == strtolower(PramatiConstants::PENDING_INVOICE_STATUS)) {
                                return $model->status . '<br>(<a href="#" data-value=' . $model->id . ' class="invoice_status" style="color:#3BAA3B;">Approve</a>)';
                            } else {
                                return "<span style='color:#3BAA3B;'>" . $model->status . "</a>";
                            }
                        })
                        ->editColumn('created_at', function ($model) {              
                            return Carbon::parse($model->created_at)->format('Y-m-d');
                        })
                        ->editColumn('address', function ($model) {              
                            return '<span title="'.$model->address.'">'.substr($model->address,0,50).'</span>';
                        })
                        ->editColumn('phone', function ($model) {              
                            return '<span title="'.$model->phone.'">'.substr($model->phone,0,10).'</span>';
                        })
                        ->addColumn('assigned_to', function($model) {
                          $assignedTo = 'N/A';
                          if(!empty($model->assigned_user_id)) {
                             $user = User::find($model->assigned_user_id);
                             $assignedTo = $user->name;    
                          }
                          return $assignedTo;
                        })
                        ->addColumn('action_info', function ($model) {
                            $closedStatus = OperationalStatus::where('slug', '=', 'closed')->first();
                            $lead_status = LeadStatus::where('lead_id', '=', $model->lead_id)->orderBy('id', 'desc')->first();
                            $actionInfo = '<a href="/admin/download_invoice/' . $model->id . '">Download</a>';
                            if ($model->assigned_user_id == '' || $model->assigned_user_id == 0 || ($lead_status->status_id == $closedStatus->id )) {
                                $actionInfo = $actionInfo . '<br><a href="/admin/edit_invoice/' . $model->id . '" class="edit_link" data-value=' . $model->id . '>Edit</a>';
                            } else {
                                $actionInfo = $actionInfo . '<br><a href="/admin/view_invoice/' . $model->id . '" class="edit_link" data-value=' . $model->id . '>View</a>';
                            }
                            return $actionInfo;
                        })
                        ->make(true);
    }

    public function downloadInvoice($invoice_id) {
        $invoice = Invoice::find($invoice_id);
        $lead = Lead::find($invoice->lead_id);
        /* $invoice = DB::table('invoices')
          ->join('orders', 'orders.id', '=', 'invoices.order_id')
          ->join('services', 'services.id', '=', 'orders.service_id')
          ->where('invoices.id','=',$invoice_id)
          ->select('invoices.*', 'services.name as service_name','services.description as service_description')
          ->get(); */
        //$invoice = $invoice[0];
        //echo var_dump($invoice->service_name);
        //die();
        //return view('admin.reports.show_invoice', array('invoice' => $invoice, 'lead' => $lead)); 
        $pdf = \PDF::loadView('admin.reports.show_invoice', ['invoice' => $invoice, 'lead' => $lead]);
        return $pdf->download('P_' . $invoice->invoice_number . '.pdf');
        //return \PDF::loadFile('http://www.github.com')->stream('github.pdf'); 
        //return \PDF::loadView('http://www.github.com', '')->download('/home/pandey/MyWorkspace/bill-123.pdf');
        //$snappy = new SnappyPdf;
        //$snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', '/home/pandey/MyWorkspace/bill-123.pdf');
    }

    public function editInvoice(Request $request,$invoice_id, IOperationRestContract $IOperationRestContract) {
        $referer = $request->input('referer');
        $invoice = Invoice::find($invoice_id);
        $closedStatus = OperationalStatus::where('slug', '=', 'closed')->first();
        $lead_status = LeadStatus::where('lead_id', '=', $invoice->lead_id)->orderBy('id', 'desc')->first();
        if($referer != 'admin'){
            if ($invoice->assigned_user_id != '' && $invoice->assigned_user_id > 0 && ($lead_status->status_id != $closedStatus->id )) {
                return redirect()->back();
            }
        }
        $edit_after_lead_closed = false;
        if ($lead_status->status_id == $closedStatus->id) {
            $edit_after_lead_closed = true;
        }
        $order = Order::find($invoice->order_id);
        $service = Service::find($invoice->service_id);
        $payment_modes = Invoice::parseDropDownValues($IOperationRestContract->getPaymentMode());
        $payment_types = Invoice::parseDropDownValues($IOperationRestContract->getPaymentType());
        $payment_periods = Invoice::parseDropDownValues($IOperationRestContract->getPaymentPeriod());
        $lead_references = Invoice::parseUserDropDownValues($IOperationRestContract->getAssignableEmployees());
        return view('admin.reports.edit_invoice', array('invoice' => $invoice, 'order' => $order, 'service' => $service, 'payment_modes' => $payment_modes, 'payment_types' => $payment_types, 'payment_periods' => $payment_periods, 'lead_references' => $lead_references, 'edit_after_lead_closed' => $edit_after_lead_closed,'referer' => $referer));    
    }

    public function viewInvoice($invoice_id, IOperationRestContract $IOperationRestContract) {
        $invoice = Invoice::find($invoice_id);
        $invoice_payment_collection = InvoicePaymentCollection::where('invoice_id', '=', $invoice_id)->first();
        $order = Order::find($invoice->order_id);
        $service = Service::find($invoice->service_id);
        $payment_modes = Invoice::parseDropDownValues($IOperationRestContract->getPaymentMode());
        $payment_types = Invoice::parseDropDownValues($IOperationRestContract->getPaymentType());
        $payment_periods = Invoice::parseDropDownValues($IOperationRestContract->getPaymentPeriod());
        $lead_references = Invoice::parseUserDropDownValues($IOperationRestContract->getAssignableEmployees());
        return view('admin.reports.view_invoice', array('invoice' => $invoice, 'order' => $order, 'service' => $service, 'payment_modes' => $payment_modes, 'payment_types' => $payment_types, 'payment_periods' => $payment_periods, 'lead_references' => $lead_references,'invoice_payment_collection' => $invoice_payment_collection));
    }

    public function updateInvoice(Request $request, BillingDomainService $billingDomainService) {
        $invoice = Invoice::find($request->input('id'));
        if ($invoice == null) {
            return Redirect::action('Link\ReportLinkController@invoices');
            //return redirect()->back();
        } else {
            $input = $request->all();
            $invoice->fill($input);
            $referer = $request->input('referer');
            $leadOrm = Lead::where('id', '=', $invoice->lead_id)->first();
            if ($request->input('invoice_to_date') != '') {
                //$toDate = new DateTime($request->input('invoice_to_date'));
                //$fromDate = new DateTime($invoice->invoice_from_date);
                //$billingDays = $fromDate->diff($toDate)->format("%a");
                if($referer && $referer == 'admin'){
                    $dateDiff = strtotime($request->input('invoice_to_date')) - strtotime($request->input('invoice_from_date'));
                    $invoice->invoice_from_date = $request->input('invoice_from_date');   
                }else{
                    $dateDiff = strtotime($request->input('invoice_to_date')) - strtotime($invoice->invoice_from_date);   
                }
                $billingDays = (floor($dateDiff / (60 * 60 * 24)) + 1);
                $payment_period = PaymentPeriod::where('id','=', $invoice->payment_period_id)->first();
                if ($payment_period->label == 'Monthly') {
                    $fromDate = new DateTime($invoice->invoice_from_date);
                    $numberOfDays = $fromDate->format("t");
                    $amount = ($request->price_per_day * $billingDays) / $numberOfDays;
                  
                } else {
                    $amount = ($request->price_per_day * $billingDays);
                }
                $invoice->total_amount = $amount;
                $invoice->billing_days = $billingDays;
            } else {
                $invoice->total_amount = ($request->price_per_day * $invoice->billing_days);
            }
            if ($invoice->assigned_user_id != null && $invoice->assigned_user_id > 0 && $referer != 'admin') {
                $user = User::find($invoice->assigned_user_id);
                $send_payment_collection_mail = $billingDomainService->sendPaymentCollectionEmail($user, $leadOrm, $invoice);
                $inv_link = "http://crm.pramaticare.com/admin/edit_invoice_payment/".$invoice->id;
                $invoice_from_date = date_format(new DateTime($invoice->invoice_from_date), "d F, Y");
                $invoice_to_date = date_format(new DateTime($invoice->invoice_to_date), "d F, Y");
                $message = urlencode($leadOrm->customer_name. ' '.$leadOrm->customer_last_name.' Rs.'.$invoice->total_amount.' '.$invoice_from_date.'-'.$invoice_to_date.' Phone No.'.$leadOrm->phone.', '.$leadOrm->address.' Please click here to Update Payment Details. '.$inv_link);
                $billingDomainService->sendSMS($user->phone, $message);     
            }
            if($invoice->payment_mode_id == 3 && $invoice->payumoney_url == ''){
                $invoice = $billingDomainService->editInvoicePaymentMode($leadOrm,$invoice);
            }
            $invoice->save();
            if($referer && $referer == 'admin'){
              $leadOrm->payment_type_id = $request->input('payment_type_id');
              $leadOrm->payment_period_id = $request->input('payment_period_id');
              $leadOrm->payment_mode_id = $request->input('payment_mode_id');
              $leadOrm->save();
            }
            return Redirect::action('Link\ReportLinkController@invoices');
            //return redirect()->back();
        }
    }

    public function approveInvoice($invoice_id, BillingDomainService $billingDomainService) {
        $invoice = Invoice::find($invoice_id);
        if ($invoice == null) {
            return redirect()->back();
        } else {
            $invoice->status = PramatiConstants::APPROVED_INVOICE_STATUS;
            $lead = Lead::find($invoice->lead_id);
            if ($invoice->assigned_user_id != null && $invoice->assigned_user_id > 0) {
                $user = User::find($invoice->assigned_user_id);
                $send_payment_collection_mail = $billingDomainService->sendPaymentCollectionEmail($user, $lead, $invoice);
                $inv_link = "http://crm.pramaticare.com/admin/edit_invoice_payment/".$invoice->id;
                $invoice_from_date = date_format(new DateTime($invoice->invoice_from_date), "d F, Y");
                $invoice_to_date = date_format(new DateTime($invoice->invoice_to_date), "d F, Y");
                $message = urlencode($lead->customer_name. ' '.$lead->customer_last_name.' Rs.'.$invoice->total_amount.' '.$invoice_from_date.'-'.$invoice_to_date.' Phone No.'.$lead->phone.', '.$lead->address.' Please click here to Update Payment Details. '.$inv_link);
                $billingDomainService->sendSMS($user->phone, $message);     
            }
            //if send mail is true then only we are saving status
            if ($invoice->save()) {
                $billingDomainService->sendInvoiceEmail($lead, $invoice);
                $message = urlencode("Dear Customer, your invoice for the current period has been mail to you. Kindly make the payment in time to enjoy uninterrupted service. Thanks, Pramaticare ");
                $billingDomainService->sendSMS($lead->phone, $message);
            }
            return redirect()->back();
        }
    }

    public function generateInvoiceManually($order_id, BillingDomainService $billingDomainService){
        $order = Order::find($order_id);
        if ($order == null) {
            return redirect()->back();
        } else {
            $invoice_count = Invoice::where('order_id',$order_id)->count();
            if($invoice_count > 0){
                //$generate_invoice = $billingDomainService->generateAutoInvoice($order->lead_id);
                $message = 'You cannot generate invoice manually for this order as initial invoice is already generated.';
            }else{
                $leadOrm = Lead::find($order->lead_id);
                $generate_invoice = $billingDomainService->generateInvoice($leadOrm, $order, 1);
                $message = 'Invoice Generated Successfully';
            }
            return redirect()->back()->with('data', $message);
        }
    }
    
    /// function to update invoice payments by field executive or payment collector
    public function assignedInvoices() {
        $loggedUser = Auth::user();
        $invoice_statuses = [PramatiConstants::PENDING_INVOICE_STATUS => 'Pending', PramatiConstants::APPROVED_INVOICE_STATUS => 'Approved'];
        $invoices = DB::table('invoices')
                ->join('orders', 'orders.id', '=', 'invoices.order_id')
                ->where('assigned_user_id', '=', $loggedUser->id)->where('invoices.status', '=', PramatiConstants::APPROVED_INVOICE_STATUS)
                ->select('invoices.date_of_collection', 'invoices.status', 'invoices.order_id', 'invoices.invoice_number', 'invoices.created_at', 'invoices.order_id', 'invoices.customer_name', 'invoices.email', 'invoices.phone', 'invoices.address', 'invoices.price_per_day', 'invoices.total_amount', 'invoices.id', 'orders.order_number')
                ->orderBy('invoices.created_at', 'DESC')
                ->get();

        return view('admin.reports.assigned_invoices', array('invoices' => $invoices, 'invoice_statuses' => $invoice_statuses,'referer' =>''));
    }
    
    public function onlineAssignedInvoices() {
        $loggedUser = Auth::user();
        $invoice_statuses = [PramatiConstants::PENDING_INVOICE_STATUS => 'Pending', PramatiConstants::APPROVED_INVOICE_STATUS => 'Approved'];
        $invoices = DB::table('invoices')
                ->join('orders', 'orders.id', '=', 'invoices.order_id')
                ->where('invoices.payumoney_url', '!=', '')
                ->where('assigned_user_id', '=', 0)
                ->where('invoices.status', '=', PramatiConstants::APPROVED_INVOICE_STATUS)
                ->select('invoices.date_of_collection', 'invoices.status', 'invoices.order_id', 'invoices.invoice_number', 'invoices.created_at', 'invoices.order_id', 'invoices.customer_name', 'invoices.email', 'invoices.phone', 'invoices.address', 'invoices.price_per_day', 'invoices.total_amount', 'invoices.id', 'orders.order_number')
                ->orderBy('invoices.created_at', 'DESC')
                ->get();

        return view('admin.reports.assigned_invoices', array('invoices' => $invoices, 'invoice_statuses' => $invoice_statuses,'referer' => 'online_invoice'));
    }

    public function editInvoicePayment($invoice_id, IOperationRestContract $IOperationRestContract) {
        $invoice = Invoice::find($invoice_id);
        $invoice_payment = new InvoicePaymentCollection();
        $service = Service::find($invoice->service_id);
        $payment_modes = Invoice::parseDropDownValues($IOperationRestContract->getPaymentMode());
        // removing payment mode type online as it is not req here
        if($invoice->payment_mode_id == 3){
          unset($payment_modes[1]);
          unset($payment_modes[2]);
        }else{
          unset($payment_modes[3]);
        }
        $payment_statuses = [PramatiConstants::PAID_PAYMENT_STATUS => 'Paid', PramatiConstants::UNPAID_PAYMENT_STATUS => 'UnPaid'];
        $unpaid_payment_reasons = [PramatiConstants::SERVICE_CLOSE => 'Service Close/Not Continue', PramatiConstants::SERVICE_ISSUE => 'Service Issue', PramatiConstants::CLIENT_NOT_AVAILABLE => 'Client Not Available', PramatiConstants::OTHERS => 'Others'];
        $outstanding_amt_types = [PramatiConstants::RECOVERABLE_OS_AMT_TYPE => 'Recoverable', PramatiConstants::NORECOVERABLE_OS_AMT_TYPE => 'Non Recoverable'];
        $nr_outstanding_amt_statuses = [PramatiConstants::PRICE_ISSUE => 'Price Issue', PramatiConstants::PART_CLOSE => 'Part Close', PramatiConstants::LEAVES_ISSUE => 'Leaves Issue', PramatiConstants::UNHAPPY => 'Unhappy with Service', PramatiConstants::BAD_DEBT => 'Bad Debt', PramatiConstants::OTHERS => 'Others'];
        return view('admin.reports.edit_invoice_payment', array('invoice' => $invoice, 'service' => $service, 'payment_modes' => $payment_modes, 'invoice_payment' => $invoice_payment, 'payment_statuses' => $payment_statuses, 'outstanding_amt_types' => $outstanding_amt_types, 'nr_outstanding_amt_statuses' => $nr_outstanding_amt_statuses, 'unpaid_payment_reasons' => $unpaid_payment_reasons));
    }

    public function createInvoicePayment(Request $request) {
        $invoice_payment = new InvoicePaymentCollection();
        $input = $request->all();
        $invoice_payment->fill($input);
        $invoice = Invoice::find($request['invoice_id']);
        $invoice->date_of_collection = Carbon::now();
        $invoice->status = $request['payment_status'].'/'.$request['outstanding_amt_type'].'/'.$request['nonrecoverable_outstanding_amt_status'];
        $invoice->remaining_amount = $request['outstanding_amt'];
        DB::transaction(function() use ($invoice_payment, $invoice) {
            $invoice_payment = $invoice_payment->save();
            $invoice = $invoice->save();
        });
        //$invoice_payment->fill($input)->save();
        return Redirect::action('Link\ReportLinkController@assignedInvoices');
        //return redirect()->back();
    }

    public function budgetMovement(Request $request) {
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        if (Input::get('end_date')) {
            $end_date = Carbon::parse(Input::get('end_date'))->format('Y-m-d');
        }
        $report_data = DB::table('invoices')
                ->leftJoin('invoice_payment_collections', 'invoice_payment_collections.invoice_id', '=', 'invoices.id')
                ->groupBy(DB::raw("STR_TO_DATE(invoices.created_at, '%Y-%m-%d')"))
                ->orderBy('invoices.created_at', 'DESC')
                ->whereBetween('invoices.created_at', array($start_date, $end_date))
                ->get(array(DB::raw('sum(total_amount) as total_amount,sum(paid_amt) as paid_amt'), 'invoices.created_at'));
        return view('admin.reports.budget_movement', array('report_data' => $report_data, 'end_date' => $end_date, 'start_date' => $start_date));
    }

    public function dailyCollection(Request $request, IOperationRestContract $IOperationRestContract) {
        $payment_types = Invoice::parseDropDownValues($IOperationRestContract->getPaymentType());
        $payment_modes = Invoice::parseDropDownValues($IOperationRestContract->getPaymentMode());
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        if (Input::get('end_date')) {
            $end_date = Carbon::parse(Input::get('end_date'))->format('Y-m-d');
        }
        $report_data = DB::table('invoice_payment_collections')
                        ->select("invoice_payment_collections.*", "invoices.invoice_number", "invoices.total_amount", "invoices.payment_type_id", "invoices.payment_mode_id", "invoices.assigned_user_id")
                        ->join('invoices', 'invoices.id', '=', 'invoice_payment_collections.invoice_id')
                        ->orderBy('invoice_payment_collections.created_at', 'DESC')
                        ->whereBetween('invoice_payment_collections.created_at', array($start_date, $end_date))->get();
        return view('admin.reports.daily_collection', array('report_data' => $report_data, 'end_date' => $end_date, 'start_date' => $start_date, 'payment_types' => $payment_types, 'payment_modes' => $payment_modes));
    }

    public function fieldExecutiveCollection(Request $request, IOperationRestContract $IOperationRestContract) {
        $lead_references = Invoice::parseUserDropDownValues($IOperationRestContract->getAssignableEmployees());
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        $assigned_user_id = Input::get('assigned_user_id');
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        if (Input::get('end_date')) {
            $end_date = Carbon::parse(Input::get('end_date'))->format('Y-m-d');
        }
        $report_data = [];
        if ($assigned_user_id) {
            $data = DB::table('invoices')
                    ->groupBy(DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d')"), 'payment_mode_id')
                    ->orderBy('invoices.created_at', 'DESC')
                    ->whereBetween('created_at', array($start_date, $end_date))
                    ->where('assigned_user_id', $assigned_user_id)
                    ->get(array(DB::raw('sum(total_amount) as total_amount,sum(remaining_amount) as remaining_amount'), DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d') as created_at"), 'payment_mode_id'));
            //$report_data =;
            for ($x = 0; $x < sizeof($data); $x++) {
                $report_data[$data[$x]->created_at][$data[$x]->payment_mode_id] = ['total_amount' => $data[$x]->total_amount, 'remaining_amount' => $data[$x]->remaining_amount];
            }
        }
        return view('admin.reports.field_executive_collection', array('report_data' => $report_data, 'end_date' => $end_date, 'start_date' => $start_date, 'lead_references' => $lead_references, 'request' => $request));
    }

    public function totalCollection(Request $request) {
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        if (Input::get('end_date')) {
            $end_date = Carbon::parse(Input::get('end_date'))->format('Y-m-d');
        }
        $report_data = [];
        $data = DB::table('invoices')
                ->groupBy(DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d')"), 'payment_mode_id')
                ->orderBy('invoices.created_at', 'DESC')
                ->whereBetween('created_at', array($start_date, $end_date))
                ->get(array(DB::raw('sum(total_amount) as total_amount,sum(remaining_amount) as remaining_amount'), DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d') as created_at"), 'payment_mode_id'));
        for ($x = 0; $x < sizeof($data); $x++) {
            $report_data[$data[$x]->created_at][$data[$x]->payment_mode_id] = ['total_amount' => $data[$x]->total_amount, 'remaining_amount' => $data[$x]->remaining_amount];
        }
        return view('admin.reports.total_collection', array('report_data' => $report_data, 'end_date' => $end_date, 'start_date' => $start_date));
    }

    public function payumoneyCallback(Request $request) {
        if ($request->paymentId) {
            $payment_transaction = \App\Models\ORM\PayumoneyTransaction::where('payment_id', $request->paymentId)->get()->first();
            if ($payment_transaction) {
                $payment_transaction->response_code = $request->merchantTransactionId;
                $payment_transaction->save();
                return response()->json(['message' => 'success']);
            } else {
                return response()->json(['message' => 'payment transaction not found']);
            }
        } else {
            return response()->json(['message' => 'please pass valid input']);
        }
    }

    public function salaryReport() {
        return view('admin.reports.salaryreport');
    }
    
    public function cgTrackingReport() {
        $start_date = Carbon::now()->subDays(1)->format('Y-m-d');
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        $report_data = DB::table('caregiver_auto_attendances')
                ->select('leads.id as lead_id','caregiver_auto_attendances.caregiver_name', DB::raw("concat(customer_name,' ',customer_last_name) as customer_name"),'mobile as cg_mobile',
                         DB::raw("case when dtmf_input = '1 <br>' then 'Present' else 'Absent' end as dtmf_input"),DB::raw("case when is_flagged = '1' then 'Flagged' else 'Not Flagged' end as is_flagged"))
                ->join('users', 'users.id', '=', 'caregiver_auto_attendances.user_id')
                ->join('lead_vendors', 'lead_vendors.assignee_user_id', '=', 'caregiver_auto_attendances.user_id')
                ->join('leads', 'leads.id', '=', 'lead_vendors.lead_id')
                ->where('leads.current_status',7)
                ->where(DB::raw("DATE_FORMAT(caregiver_auto_attendances.created_at,'%Y-%m-%d')"), $start_date)
                ->orderBy('customer_name')
                ->distinct()
                ->get();
        //echo dump($report_data);
        //die();
        return view('admin.reports.cg_tracking_report', array('report_data' => $report_data, 'start_date' => $start_date));
    }
    
    public function cgAttendanceReport(Request $request) {
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        $lead_id = Input::get('lead_id');
        $leads = \App\Models\ORM\LeadFeedback::getLeadsForDropdown();
        if (Input::get('start_date')) {
            $start_date = Carbon::parse(Input::get('start_date'))->format('Y-m-d');
        }
        if (Input::get('end_date')) {
            $end_date = Carbon::parse(Input::get('end_date'))->format('Y-m-d');
        }
        $report_data = DB::table('lead_vendor_attendance')
                ->select('date','name',DB::raw("case when is_present = '1' then 'Present' else 'Absent/NA' end as is_present"))
                ->join('user_vendors', 'user_vendors.id', '=', 'lead_vendor_attendance.vendor_id')
                ->join('users', 'users.id', '=', 'user_vendors.user_id')
                ->orderBy('date', 'asc')
                ->where('user_type_id',2)
                ->where('lead_id','=',$lead_id)
                ->whereBetween('lead_vendor_attendance.date', array($start_date, $end_date))
                ->distinct()
                ->get();
        return view('admin.reports.cg_attendance_report', array('report_data' => $report_data, 'end_date' => $end_date, 'start_date' => $start_date,'lead_id' => $lead_id,'leads' => $leads));
    }

}
