<?php

namespace App\Services\Domain;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\AgeRange;
use App\Models\ORM\Complaint;
use App\Models\ORM\ComplaintsCategories;
use App\Models\ORM\Condition;
use App\Models\ORM\CustomerFeedback;
use App\Models\ORM\CustomerNotification;
use App\Models\ORM\CustomerStatusRequest;
use App\Models\ORM\CustomerVendorAttendance;
use App\Models\ORM\CustomerVendorStatus;
use App\Models\ORM\Enquiry;
use App\Models\ORM\Ailment;
use App\Models\ORM\Equipment;
use App\Models\ORM\FoodType;
use App\Models\ORM\Gender;
use App\Models\ORM\Language;
use App\Models\ORM\Lead;
use App\Models\ORM\LeadActiveDate;
use App\Models\ORM\LeadApprovalEscalation;
use App\Models\ORM\LeadComment;
use App\Models\ORM\ComplaintLogs;
use App\Models\ORM\LeadEmployee;
use App\Models\ORM\LeadFieldEmployee;
use App\Models\ORM\LeadPrice;
use App\Models\ORM\LeadQCBriefing;
use App\Models\ORM\LeadQCEmployee;
use App\Models\ORM\LeadReference;
use App\Models\ORM\LeadSource;
use App\Models\ORM\LeadStatus;
use App\Models\ORM\LeadVendor;
use App\Models\ORM\LeadVendorAttendance;
use App\Models\ORM\LeadVendorCustomerSignOff;
use App\Models\ORM\LeadVendorEvaluation;
use App\Models\ORM\LeadVendorTask;
use App\Models\ORM\LeadVendorTrainingEvaluation;
use App\Models\ORM\LeadWatcher;
use App\Models\ORM\Modality;
use App\Models\ORM\OperationalStatus;
use App\Models\ORM\OperationalStatusGroup;
use App\Models\ORM\Patient;
use App\Models\ORM\PatientAilment;
use App\Models\ORM\PatientEquipment;
use App\Models\ORM\PatientPhysiotherapy;
use App\Models\ORM\PatientTask;
use App\Models\ORM\PatientValidation;
use App\Models\ORM\PatientValidationTask;
use App\Models\ORM\Prescription;
use App\Models\ORM\Ptcondition;
use App\Models\ORM\Religion;
use App\Models\ORM\Service;
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
use Vluzrmos\SlackApi\Facades\SlackChannel;
use App\Models\ORM\Incentives;
use App\Services\Domain\VendorDomainService;
use App\Models\ORM\VendorsRating;
use App\Models\ORM\CgReplacement;

class OperationDomainService implements IOperationDomainContract
{

    public function getServices($sorted=false){
        if($sorted){
            return Service::orderBy('sort_order','ASC')->get();
        }
        return Service::get();
    }
    public function getLeadReferencesList(){
        $x= LeadReference::where('parent_id','=',0)->with('childrens')->get();
        return $x;
    }

    public function getShifts() {
        return Shift::get();
    }

    public function submitLeadEnquiry($name, $email, $phone,$localityOrm,$serviceId){
        $newEnquiry = new Enquiry();
        $newEnquiry->name = $name;
        $newEnquiry->email = $email;
        $newEnquiry->phone = $phone;
        $localityOrmCreated = $this->createLocalityByOrm($localityOrm);

        $leadOrm = new Lead();
        $leadOrm->service_id = $serviceId;
        $leadOrm->customer_name =$name;
        $leadOrm->phone = $phone;
        $leadOrm->email = $email;
        if($localityOrmCreated){
            $leadOrm->locality_id = $localityOrmCreated->id;
        }else{
            $leadOrm->locality_id = 0;
        }

        // get online lead srouce id
        $leadSource = LeadSource::where('slug','=','online')->first();
        $leadOrm->source_id = $leadSource->id;

        $leadOrm->save();
        return $leadOrm;
        die();


        return $newEnquiry;
    }
    public function generateOtpForPhone($leadId,$phone){
        $otpGenerated = rand (100000,999999);
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->otp = $otpGenerated;
        $leadOrm->save();
        return $this->sendSMS($phone,urlencode("Your OTP is ".$otpGenerated),13);
    }
    public function verifyLeadOtp($leadId,$otp){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        if($leadOrm->otp == $otp){
            $leadOrm->otp_verified = true;
            $leadOrm->save();
            return true;
        }
        return false;
    }

    public function callLead($leadId,$supportUserId){
        $leadInfo = Lead::where('id','=',$leadId)->first();

        if($leadInfo->otp_verified && $leadInfo->phone){
            //extract data from the post
            //echo $leadInfo->phone;
            $supportUserId = env("MYOPERATOR_SUPPORT_USERID");
            //set POST variables
            $url = env('MYOPERATOR_URL');
            $fields_string = '';
            $fields = array(
                'token' => urlencode(env('MYOPERATOR_TOKEN')),
                'customer_number' => urlencode($leadInfo->phone),
                'customer_cc' => urlencode(env('MYOPERATOR_COUNTRY_CODE')),
                'support_user_id' => urlencode($supportUserId)
            );
            //d($fields);

            //url-ify the data for the POST
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);

            $leadInfo->call_initiated_at = Carbon::now();
            $leadInfo->save();

        }else{
            // phone number not verified
            echo "Customer phone number not verified";
            return false;
        }
    }

    public function automatedCallLead($leadId,$supportUserId){
        $leadInfo = Lead::where('id','=',$leadId)->first();

        if($leadInfo->phone){

            Log::info('Connecting to call Bholu');
            //return;
            //extract data from the post
            //echo $leadInfo->phone;
            $supportUserId = env("MYOPERATOR_SUPPORT_USERID");
            //set POST variables
            $url = env('MYOPERATOR_URL');
            $fields_string = '';
            $fields = array(
                'token' => urlencode(env('MYOPERATOR_TOKEN')),
                'customer_number' => urlencode($leadInfo->phone),
                'customer_cc' => urlencode(env('MYOPERATOR_COUNTRY_CODE')),
                'support_user_id' => urlencode($supportUserId)
            );
            //d($fields);

            //url-ify the data for the POST
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            $leadInfo->call_initiated_at = Carbon::now();
            $leadInfo->save();

        }else{
            // phone number not verified
            echo "Customer phone number not verified";
            return false;
        }
    }

    public function sendSMS($phone,$message,$type=null){
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
        echo $curl_scraped_page;
        curl_close($ch);
        return true;
    }



    private function sendWelcomeMail($name,$email){
        $mail = new \PHPMailer();



//$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = env('PHPMAILER_HOST');  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = env('PHPMAILER_USERNAME');                 // SMTP username
        $mail->Password = env('PHPMAILER_PASSWORD');                           // SMTP password
        $mail->SMTPSecure = 'none';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = env('PHPMAILER_PORT');                                    // TCP port to connect to

        $mail->setFrom(env('PHPMAILER_FROM_EMAIL'), env('PHPMAILER_FROM_NAME'));
        $mail->addAddress($email, $name);     // Add a recipient
        $mail->addReplyTo(env('PHPMAILER_REPLY_EMAIL'), env('PHPMAILER_REPLY_NAME'));
        // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Thank you for choosing Pramati Care';
        $mail->Body    = view('emails.welcome');

        if(!$mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function sendNewLeadNotification($leadId){

        $leadDetail = $this->getLeadDetailedOrm($leadId);
        return;
        //echo $leadDetail->customerName." ".$leadDetail->customerLastName;
        $messageForCustomer =urlencode(str_replace("|:name:|",($leadDetail->customer_name." ".$leadDetail->customer_last_name),env('SMS_STR_NEW_LEAD_CUSTOMER')));
        $this->sendSMS($leadDetail->phone,$messageForCustomer);

        // email to customer
        //$this->sendWelcomeMail(($leadDetail->customer_name." ".$leadDetail->customer_last_name),$leadDetail->email);



        return;



    }

    public function sendMailToCustomer($leadId){
        $leadDetail = $this->getLeadDetailedOrm($leadId);
        /*if($leadDetail->patient && $leadDetail->patient->id){
            $validationData = $this->patientValidationData($leadDetail->patient->id);
        }else{
            $validationData = null;
        }*/

        //dd($leadDetail);
        $leadDetail = $this->getLeadDetailedOrm($leadId);

        $ccList = [env('TESTING_MAIL')];
        if(env('APP_ENV')=="production") {
            $emails = ['cs@pramaticare.com', 'vishal@pramaticare.com','neetu@pramaticare.com','richa@pramaticare.com'];
            array_push($ccList,$leadDetail->userCreated->email);
        }else{
            $emails = [env('TESTING_MAIL')];
        }

        $mailSubject = 'New lead generated: '.$leadDetail->customer_name;

        $data = ['emailTo'=>$emails, 'subject'=>$mailSubject,'emailCCTo'=>$ccList];

        $mailView = view('emails.customer_mailer')->with('lead',$leadDetail);

        Mail::queue('emails.customer_mailer', ['lead' => $leadDetail,'leadUrl'=>url('lead/'.$leadDetail->id)], function ($m) use ($data) {
            $m->from(env('PHPMAILER_FROM_EMAIL'),  env('PHPMAILER_FROM_NAME'));
            $m->to($data['emailTo'])->cc($data['emailCCTo'])->subject($data['subject']);
        });


        return view('emails.customer_mailer')->with('lead',$leadDetail)->with('leadUrl',$leadDetail->id);

    }

    public function submitLeadEnquiryNotification($leadId,$name, $email, $phone,$localityOrm,$serviceId){

        $messageForCustomer =urlencode(str_replace("|:name:|",$name,env('SMS_STR_NEW_LEAD_CUSTOMER')));
        $this->sendSMS($phone,$messageForCustomer);

        // email to customer
        //$this->sendWelcomeMail($name,$email);


        // email to client servicing team and admins
        //$this->sendNewLeadNotification($leadId);



    }

    public function submitEnquiry($name, $email, $phone,$message){
        $newEnquiry = new Enquiry();
        $newEnquiry->name = $name;
        $newEnquiry->email = $email;
        $newEnquiry->phone = $phone;
        $newEnquiry->message = $message;

        $newEnquiry->save();
        return $newEnquiry;
    }

    public function getEnquiryById($enquiryId){
        return Enquiry::find($enquiryId);
    }

    public function getUserOrm($userId){
        return User::where('id','=',$userId)->first();
    }

    public function createLocalityByOrm($localityOrm){
        try{
            if(!$localityOrm){
                return ;
            }
            $localityOrm->save();
            return $localityOrm;
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function createLeadByOrm($leadOrm){
        try{
            $leadOrm->save();
            return $leadOrm;
        }catch (Exception $ex){
            return false;
        }
    }
    public function updateLeadByOrm($leadId,$leadOrm){
        try{
            $leadObject = Lead::where('id','=',$leadId)->first();
            $leadObject->service_id = $leadOrm->service_id;
            $leadObject->lead_status_id = $leadOrm->lead_status_id;
            $leadObject->service_sub_category_id = $leadOrm->service_sub_category_id;
            $leadObject->customer_name = $leadOrm->customer_name;
            $leadObject->customer_last_name = $leadOrm->customer_last_name;
            $leadObject->locality_id = $leadOrm->locality_id;
            $leadObject->address = $leadOrm->address;
            $leadObject->landmark = $leadOrm->landmark;
            $leadObject->city_id = $leadOrm->city_id;
            $leadObject->email = $leadOrm->email;
            $leadObject->phone = $leadOrm->phone;
            $leadObject->reference_id = $leadOrm->reference_id;
            $leadObject->user_id = $leadOrm->user_id;
            $leadObject->source_id = $leadOrm->source_id;
            $leadObject->start_date = $leadOrm->start_date;
            $leadObject->number_of_days = $leadOrm->number_of_days;
            $leadObject->payment_mode_id = $leadOrm->payment_mode_id;
            $leadObject->payment_type_id = $leadOrm->payment_type_id;
            $leadObject->payment_period_id = $leadOrm->payment_period_id;
            $leadObject->task_other = $leadOrm->task_other;
            $leadObject->remark = $leadOrm->remark;
            $leadObject->save();
            return $leadOrm;
        }catch (Exception $ex){
            return false;
        }
    }
    public function createPatientByOrm($patientOrm){
        try{
            $patientOrm->save();
            return $patientOrm;
        }catch (Exception $ex){
            return false;
        }
    }
    public function addPatientAilment($patientId,$ailmentList){
        $data = array();

        foreach($ailmentList as $tempAilment){
            array_push($data,array('patient_id'=>$patientId,'ailment_id'=>$tempAilment['id']));
        }
        try{
            PatientAilment::insert($data);
            return true;
        }catch (\SQLiteException $ex){
            return false;
        }
        return true ;
    }
    public function addPatientEquipmentDetail($patientId,$equipmentList){
        $data = array();

        foreach($equipmentList as $tempequipment){
            array_push($data,array('patient_id'=>$patientId,'equipment_id'=>$tempequipment['id']));
        }
        try{
            PatientEquipment::insert($data);
            return true;
        }catch (\SQLiteException $ex){
            return false;
        }
        return true ;
    }

    public function addPatientPrescription($patientId,$prescriptionList){
        $data = array();

        foreach($prescriptionList as $tempPrescription){
            $prescriptionOrm = Prescription::where('id','=',$tempPrescription['id'])->first();
            $prescriptionOrm->patient_id = $patientId;
            $prescriptionOrm->save();
        }
        return true ;
    }

    public function getAilments() {
        return Ailment::orderBy('name','asc')->get();
    }

    public function getEquipments() {
        return Equipment::get();
    }

    public function getLanguages() {
        return Language::get();
    }

    public function getReligions() {
        return Religion::get();
    }
    public function getAgeRanges() {
        return AgeRange::get();
    }
    public function getFoodTypes() {
        return FoodType::get();
    }

    public function getConditions() {

        $condition0 = Condition::get();
        return $condition0;
    }

    public function getComplaints() {
        return Complaint::get();
    }

    public function getPtconditions() {
        return Ptcondition::get();
    }

    public function getModalities() {
        return Modality::get();
    }

    public function getAilmentList($serviceId) {
        $serviceOrm = Service::with('ailments')->where('id','=',$serviceId)->first();
        return $serviceOrm->ailments;
    }

    public function getTaskList($ailmentId) {
        return Task::get();
    }


    public function getTasksByService($serviceId){
        $serviceitem = Service::with('tasks')->where('id','=',$serviceId)->first();
        return $serviceitem->tasks;
    }

    public function getAllTask(){
        return Task::get();
    }

    public function getEnquiryList(){
        return Enquiry::get();
    }
    public function getLeadList($limit=null){
        if($limit){
            return Lead::with('enquiry')
                ->with('employeesAssigned')
                ->with('primaryVendorsAssigned')
                ->with('vendorsAssigned')
                ->with('statuses')
                ->with('approvalStatus')
                ->orderBy('created_at','desc')
                ->take($limit)
                ->get();
        }
        return Lead::with('enquiry')
            ->with('employeesAssigned')
            ->with('primaryVendorsAssigned')
            ->with('vendorsAssigned')
            ->with('statuses')
            ->with('approvalStatus')
            ->orderBy('created_at','desc')
            ->take(500)
            ->get();
    }

    public function getLeadListNullUserId(){
        return Lead::whereNull('user_id')
            ->orderBy('created_at','desc')
            ->get();
    }

    public function getLeadByStatus($statusId){
        return Lead::where('current_status','=',$statusId)
            ->with('enquiry')
            ->with('employeesAssigned')
            ->with('primaryVendorsAssigned')
            ->with('vendorsAssigned')
            ->with('statuses')
            ->with('approvalStatus')
            ->orderBy('created_at','desc')
            ->get();
    }
    public function getLeadCountByStatus($statusId){
        return Lead::where('current_status','=',$statusId)->count();
    }
    public function getTodayLead(){
        $carbonNow = Carbon::now();
        $startDate = Carbon::today();
        $endDate = Carbon::tomorrow();

        return Lead::whereBetween('start_date',array($startDate,$endDate))
            ->with('enquiry')
            ->with('employeesAssigned')
            ->with('primaryVendorsAssigned')
            ->with('vendorsAssigned')
            ->with('statuses')
            ->with('approvalStatus')
            ->orderBy('created_at','desc')
            ->get();
    }
    public function getClosedLeadList(){
        $statusOfHoldOrClosed = OperationalStatus::whereIn('slug',array('closed','hold'))->get();
        $statusIdList = array();
        foreach($statusOfHoldOrClosed as $tempStatus){
            array_push($statusIdList,$tempStatus->id);
        }
        //d($statusIdList);
        $leadStatus = LeadStatus::whereIn('status_id',$statusIdList)
            ->orderBy('created_at','desc')
            ->groupBy('lead_id')
            ->with('lead')
            ->with('lead.service')
            ->with('comment')
            ->with('status')
            ->with('user')
            ->with('reason')
            ->get();

        $sortedArray = array();
        foreach($leadStatus as $temp){
            if(in_array($temp->status_id,$statusIdList)){
                array_push($sortedArray,$temp);
            }
        }
        return $sortedArray;
    }
    public function getLeadDetail($leadId){
        return Lead::where('id','=',$leadId)->with('enquiry')
            ->first();
    }
    public function getPatientDetail($patientId){
        return Patient::where('id','=',$patientId)->with('tasks')
            ->first();
    }
    public function getLeadDetailedOrm($leadId){
        return Lead::where('id','=',$leadId)->with('enquiry')
            ->with('prices.priceUnit')
            ->with('patient')
            ->with('userCreated')
            ->with('prices')
            ->with('patient.tasks')
            ->with('patient.genderItem')
            ->with('patient.shift')
            ->with('patient.agePreferred')
            ->with('patient.religionPreferred')
            ->with('patient.languagePreferred')
            ->with('patient.foodPreferred')
            ->with('patient.equipment')
            ->with('patient.ailments')
            ->with('patient.mobilityItem')
            ->with('employeesAssigned')
            ->with('primaryVendorsAssigned')
            ->with('vendorsAssigned')
            ->with('qcAssigned')
            ->with('statuses')
            ->with('leadReference')
            ->with('leadSource')
            ->with('paymentType')
            ->with('paymentPeriod')
            ->with('paymentMode')
            ->with('locality')
            ->with('service')
            ->with('leadReference')
            ->with('patientAilment')
            ->with('prices')
            ->first();
    }
    public function getLeadCommentsCollection($leadId){
        return LeadComment::where('lead_id','=',$leadId)
            ->with('user')
            ->with('slackEmployee')
            ->with('slackEmployee.user')
            ->get();
    }

    public function getComplaintLogs($leadId){
        return ComplaintLogs::where('lead_id','=',$leadId)
            ->with('user')
            ->get();
    }

    public function submitLeadComment($leadId,$comment,$userId){
        $commentObject = new LeadComment();
        $commentObject->lead_id = $leadId;
        $commentObject->comment = $comment;
        $commentObject->user_id = $userId;
        $commentObject->attachment = false;
        try{
            $commentObject->save();
            return LeadComment::where('id','=',$commentObject->id)->with('lead')->with('user')->with('user.employeeInfo')->first();
            //return true;
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function getLeadEmployeeAssignmentCollection($leadId){
        return LeadEmployee::where('lead_id','=',$leadId)
            ->with('assignee')
            ->with('assignedBy')
            ->get();
    }
    public function getLeadQcEmployeeAssignmentCollection($leadId){
        return LeadQCEmployee::where('lead_id','=',$leadId)
            ->with('assignee')
            ->with('assignedBy')
            ->get();
    }
    public function getLeadFieldEmployeeAssignmentCollection($leadId){
        return LeadFieldEmployee::where('lead_id','=',$leadId)
            ->with('assignee')
            ->with('assignedBy')
            ->get();
    }
    public function getLeadVendorAssignmentCollection($leadId){
        return LeadVendor::where('lead_id','=',$leadId)
            ->with('assignee')
            ->with('assignedBy')
            ->get();
    }
    public function getPrimaryLeadVendorAssignmentCollection($leadId){
        return LeadVendor::where('lead_id','=',$leadId)
            ->where('is_primary','=', true)
            ->with('assignee')
            ->with('assignedBy')
            ->get();
    }
    public function getLeadStatusChangeLog($leadId){
        return LeadStatus::where('lead_id','=',$leadId)
            ->with('user')
            ->with('status')
            ->get();
    }

    public function getLeadVendorAttendanceLog($leadId){
        return LeadVendorAttendance::where('lead_id','=',$leadId)
            ->with('vendor')
            ->with('user')
            ->get();
    }

    public function getAssignableEmployeesCollection(){
        return UserType::where('slug','=','employee')
            ->with('users')
            ->first();
    }


    public function assignEmployeeToLead($leadId, $assingeeId,$userId){

        $leadEmployeeAssignment = new LeadEmployee();
        $leadEmployeeAssignment->lead_id=$leadId;
        $leadEmployeeAssignment->assignee_user_id = $assingeeId;
        $leadEmployeeAssignment->assigned_by_user_id = $userId;
        try{
            $leadEmployeeAssignment->save();

            return User::where('id','=',$assingeeId)->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function assignQcEmployeeToLead($leadId, $assingeeId,$userId){

        $leadEmployeeAssignment = new LeadQCEmployee();
        $leadEmployeeAssignment->lead_id=$leadId;
        $leadEmployeeAssignment->assignee_user_id = $assingeeId;
        $leadEmployeeAssignment->assigned_by_user_id = $userId;

        try{
            $leadEmployeeAssignment->save();
            $leadOrm = Lead::where('id','=',$leadId)->first();
            $leadOrm->qc_assigned_at = Carbon::now();

            $leadOrm->save();
            return User::where('id','=',$assingeeId)->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function assignFieldEmployeeToLead($leadId, $assingeeId,$userId){

        $leadEmployeeAssignment = new LeadFieldEmployee();
        $leadEmployeeAssignment->lead_id=$leadId;
        $leadEmployeeAssignment->assignee_user_id = $assingeeId;
        $leadEmployeeAssignment->assigned_by_user_id = $userId;

        try{
            $leadEmployeeAssignment->save();
            return User::where('id','=',$assingeeId)->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function assignVendorToLead($leadId, $assingeeId,$pricePerDay,  $isPrimary,$userId,$sourcingData){

        $leadEmployeeAssignment = new LeadVendor();
        $leadEmployeeAssignment->lead_id=$leadId;
        $leadEmployeeAssignment->assignee_user_id = $assingeeId;
        $leadEmployeeAssignment->assigned_by_user_id = $userId;
        $leadEmployeeAssignment->is_primary = $isPrimary;
        $leadEmployeeAssignment->price_per_day = $pricePerDay;

        try{
            $leadEmployeeAssignment->save();

            $leadOrm = Lead::where('id','=',$leadId)->first();
            $leadOrm->cg_assigned_at = Carbon::now();

            $leadOrm->save();

            if(count($sourcingData)>0){
                $taskListToInsert = array();
                foreach($sourcingData as $tempTask){
                    array_push($taskListToInsert,array(
                        'lead_vendor_id'=>$leadEmployeeAssignment->id,
                        'task_id'=>$tempTask
                    ));
                }
                LeadVendorTask::insert($taskListToInsert);
            }


            return User::where('id','=',$assingeeId)->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function submitQcBriefingDetails($leadId,$qcAssignmentId,$briefingData,$userId){
        // get $qcAssignmentId

        $assignmentObj = LeadQCEmployee::whereRaw('lead_id = ? and assignee_user_id = ? ', array($leadId,$qcAssignmentId))->orderBy('created_at','desc')->first();
        if(!$assignmentObj){
            return true;
        }
        $deletedOldBriefings = LeadQCBriefing::where('lead_qc_id','=',$assignmentObj->id)->delete();
        $taskListToInsert = array();
        foreach($briefingData as $tempTask){
            array_push($taskListToInsert,array(
                'lead_qc_id'=>$assignmentObj->id,
                'task_id'=>$tempTask,
                'brief_by_user_id'=>$userId
            ));
        }
        LeadQCBriefing::insert($taskListToInsert);
        return true;
    }

    public function getOperationalStatusesGroupList(){
        return OperationalStatusGroup::with('statuses')->with('statuses.reasons')->get();
    }

    public function getStatusBySlug($statusSlug){
        return OperationalStatus::where('slug','=',$statusSlug)->first();
    }


    public function updateLeadStatus($leadId,$statusId,$userId,$reasonId=null,$statusComment=null,$statusData=null, $statusDate=null){



        $leadStatus = new LeadStatus();
        $leadDetailed = $this->getLeadDetailedOrm($leadId);
        $currentStatus = $leadDetailed->statuses->last();
        if($currentStatus && $currentStatus->id==$statusId){
            return $currentStatus;
        }
        $leadStatus->lead_id = $leadId;
        $leadStatus->status_id = $statusId;
        $leadStatus->user_id = $userId;
        $leadStatus->status_date = $statusDate;
        if($reasonId){
            $leadStatus->reason_id = $reasonId;
        }
        if($statusComment){
            $leadStatus->comment = $statusComment;
        }
        if($statusData){
            $leadStatus->data = json_encode($statusData);
        }
        try{
            $leadStatus->save();

            $leadOrm = Lead::where('id','=',$leadId)->first();
            $leadOrm->current_status = $statusId;
            $leadOrm->save();

            return LeadStatus::where('id','=',$leadStatus->id)->with('status')->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }


    public function markLeadStarted($leadId,$datetime){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        if($datetime){
            $leadOrm->started_service_at = Carbon::parse($datetime);
        }else{
            $leadOrm->started_service_at = Carbon::now();
        }
        $leadOrm->save();
    }



    public function approveSalesLead($leadId,$userId){
        $leadApproval = OperationalStatus::where('slug','=','lead-approved')->first();
        $leadStatus = new LeadStatus();
        $leadStatus->lead_id = $leadId;
        $leadStatus->status_id = $leadApproval->id;
        $leadStatus->user_id = $userId;
        try{
            $leadStatus->save();
            return LeadStatus::where('id','=',$leadStatus->id)->with('status')->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function getAilmentTaskByServiceId($serviceId){
        return Ailment::with('tasks')->orderBy('label','desc')->get();
    }

    public function updateLeadStartDate($leadId,$startDate){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->start_date = $startDate;
        try{
            $leadOrm->save();
            return true;
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function updatePatientInfo($leadId,$patientInfo){
        $leadOrm = Lead::where('id','=',$leadId)->with('patient')->with('patient.ailments')->first();
        $patientOrm=null;
        $patientUpdated = false;
        $patientAilmentUpdated =false;
        if(!$leadOrm->patient){
            //echo "no patient info available";
            $patientOrm= new Patient();
            $patientOrm->lead_id=$leadId;
        }else {
            //echo " update patient";
            $patientOrm = Patient::where('id', '=', $leadOrm->patient->id)->with('ailments')->first();

        }

        if ($patientOrm) {
            //d($patientInfo['gender']);
            if (isset($patientInfo['gender']['id'])) {
                $patientOrm->gender = $patientInfo['gender']['id'];
            }else if($patientInfo['gender']){
                $patientOrm->gender = $patientInfo['gender'];
            }

            $patientOrm->age = $patientInfo['age'];
            $patientOrm->weight = $patientInfo['weight'];
            if (isset($patientInfo['equipmentSupport'])) {
                $patientOrm->is_on_equipment = $patientInfo['equipmentSupport'];
            }
            if (isset($patientInfo['equipments']) && isset($patientInfo['equipments']['id'])) {
                $patientOrm->equipment_id = $patientInfo['equipments']['id'];
            }
            if (isset($patientInfo['name'])) {
                $patientOrm->name = $patientInfo['name'];
            }
            if (isset($patientInfo['shift']) && isset($patientInfo['shift']['id'])) {
                $patientOrm->shift_id = $patientInfo['shift']['id'];
            }
            if (isset($patientInfo['mobility']) && isset($patientInfo['mobility']['id'])) {
                $patientOrm->mobility_id = $patientInfo['mobility']['id'];
            }
            $newDateData = Carbon::now();
            if (isset($patientInfo['morningWakeupTime'])) {
                $newDateData->timestamp(strtotime($patientInfo['morningWakeupTime']));
                $newDateData->second = 0;
                $patientOrm->morning_wakeup_time = $newDateData->toTimeString();
            }
            if (isset($patientInfo['morningBreakfastTime'])) {
                $newDateData->timestamp(strtotime($patientInfo['morningBreakfastTime']));
                $newDateData->second = 0;
                $patientOrm->breakfast_time = $newDateData->toTimeString();
            }
            if (isset($patientInfo['lunchTime'])) {
                $newDateData->timestamp(strtotime($patientInfo['lunchTime']));
                $newDateData->second = 0;
                $patientOrm->lunch_time = $newDateData->toTimeString();
            }
            if (isset($patientInfo['dinnerTime'])) {
                $newDateData->timestamp(strtotime($patientInfo['dinnerTime']));
                $newDateData->second = 0;
                $patientOrm->dinner_time = $newDateData->toTimeString();
            }
            if (isset($patientInfo['walkingTime'])) {
                $newDateData->timestamp(strtotime($patientInfo['walkingTime']));
                $newDateData->second = 0;
                $patientOrm->walk_time = $newDateData->toTimeString();
            }
            if (isset($patientInfo['walkingLocation'])) {
                $patientOrm->walk_location = $patientInfo['walkingLocation'];
            }
            if (isset($patientInfo['otherAilment'])) {
                $patientOrm->other_ailment = $patientInfo['otherAilment'];
            }
            //TODO: more info to save
            try {
                $patientOrm->save();
            } catch (\SQLiteException $ex) {
                return false;
            }
        }

        if (isset($patientInfo['ailments']) && count($patientInfo['ailments']) > 0) {
            if ($patientOrm && $patientOrm->ailments && $patientOrm->ailments->count() > 0) {
                // ailment exist
                $mappedArr = array();

                foreach ($patientOrm->ailments as $tempAilment) {
                    $mappedArr[$tempAilment->id] = true;
                }
                $toAddAilment = array();
                foreach ($patientInfo['ailments'] as $tempAil) {

                    if (isset($tempAil['id']) && isset($mappedArr[$tempAil['id']])) {
                        $mappedArr[$tempAil['id']] = false;
                    } else {
                        array_push($toAddAilment, $tempAil);
                    }
                }
                foreach ($mappedArr as $key => $tempAil) {
                    if ($tempAil == true) {
                        // delete it
                        $patAIlOrm = PatientAilment::whereRaw('patient_id = ? and ailment_id = ?', array($patientOrm->id, $key))->first();
                        $patAIlOrm->delete();
                    }
                }
                $toInsertArr = array();
                foreach ($toAddAilment as $tempAil) {
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    $tempArr['ailment_id'] = $tempAil;
                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr, $tempArr);
                }
                //d($toInsertArr);
                PatientAilment::insert($toInsertArr);
                return $patientOrm;
            } else {
                // add new ailments
                $toInsertArr = array();
                foreach ($patientInfo['ailments'] as $tempAilment) {
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    if(isset($tempAilment['id'])){
                        $tempArr['ailment_id'] = $tempAilment['id'];
                    }else{
                        $tempArr['ailment_id'] = $tempAilment;
                    }

                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr, $tempArr);
                }
                //d($toInsertArr);
                try {
                    PatientAilment::insert($toInsertArr);
                    return $patientOrm;
                } catch (\SQLiteException $ex) {
                    return false;
                }
            }
            }


        if (isset($patientInfo['equipments']) && count($patientInfo['equipments']) > 0) {
            if ($patientOrm && $patientOrm->equipments && $patientOrm->equipments->count() > 0) {
                // ailment exist
                $mappedArr = array();
                foreach ($patientOrm->equipments as $tempEquipment) {
                    $mappedArr[$tempEquipment->id] = true;
                }
                $toAddEquipment = array();
                // d($mappedArr);
                foreach ($patientInfo['equipments'] as $tempAil) {
                    if (isset($mappedArr[$tempAil['id']])) {
                        $mappedArr[$tempAil['id']] = false;
                    } else {
                        array_push($toAddEquipment, $tempAil['id']);
                    }
                }
                foreach ($mappedArr as $key => $tempAil) {
                    if ($tempAil == true) {
                        // delete it
                        $patAIlOrm = PatientEquipment::whereRaw('patient_id = ? and equipment_id = ?', array($patientOrm->id, $key))->first();
                        $patAIlOrm->delete();
                    }
                }
                $toInsertArr = array();
                foreach ($toAddEquipment as $tempAil) {
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    $tempArr['equipment_id'] = $tempAil;
                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr, $tempArr);
                }
                PatientEquipment::insert($toInsertArr);
                return $patientOrm;
            } else {
                // add new ailments
                $toInsertArr = array();
                foreach ($patientInfo['equipments'] as $tempEquipment) {
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    $tempArr['equipment_id'] = $tempEquipment;
                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr, $tempArr);
                }
                try {
                    PatientEquipment::insert($toInsertArr);
                    return $patientOrm;
                } catch (\SQLiteException $ex) {
                    return false;
                }
            }
        }

        return $patientOrm;


    }
    public function updatePhysioInfoCRM($leadId,$physioInfoOrm){
        $leadOrm = Lead::where('id','=',$leadId)->with('patient')->with('patient.physiotherapy')->first();
        $patientOrm=null;
        if(!$leadOrm->patient){
            // no patient info available
            $patientOrm= new Patient();
            $patientOrm->lead_id=$leadId;
            $patientOrm->save();
        }else{
            // update patient

            $patientOrm = Patient::where('id','=',$leadOrm->patient->id)->with('physiotherapy')->first();
            //d($patientOrm);
        }
        $physioPatientOrm=null;
        //d($patientOrm);
        if($patientOrm && $patientOrm->physiotherapy){
            // physiotherapy exist
            $physioPatientOrm = PatientPhysiotherapy::where('id','=',$patientOrm->physiotherapy->id)->first();

        }else{
            // add new physiotherapy
            $physioPatientOrm = new PatientPhysiotherapy();
            $physioPatientOrm->patient_id = $patientOrm->id;
        }
        try{
            //d($physioInfoOrm);
            $physioPatientOrm->condition_id=$physioInfoOrm->condition_id;
            $physioPatientOrm->present_condition = $physioInfoOrm->present_condition;
            $physioPatientOrm->pain_severity = $physioInfoOrm->pain_severity;
            $physioPatientOrm->motion_range = $physioInfoOrm->motion_range;
            $physioPatientOrm->modality_id = $physioInfoOrm->modality_id;
            $physioPatientOrm->no_of_sessions = $physioInfoOrm->no_of_sessions;

            $physioPatientOrm->save();
            return $patientOrm;
        }catch (\SQLiteException $ex){
            return false;
        }
    }
    public function updatePhysioPatientInfo($leadId,$patientInfo){
        $leadOrm = Lead::where('id','=',$leadId)->with('patient')->with('patient.physiotherapy')->first();
        $patientOrm=null;
        $patientUpdated = false;
        $patientAilmentUpdated =false;
        if(!$leadOrm->patient){
            // no patient info available
            $patientOrm= new Patient();
            $patientOrm->lead_id=$leadId;
        }else{
            // update patient

            $patientOrm = Patient::where('id','=',$leadOrm->patient->id)->with('physiotherapy')->first();
            //d($patientOrm);
        }
        if($patientOrm){
            $patientOrm->gender = $patientInfo['gender'];
            $patientOrm->age = $patientInfo['age'];
            $patientOrm->weight = $patientInfo['weight'];
            $patientOrm->name = $patientInfo['name'];
            //TODO: more info to save
            try{
                $patientOrm->save();
            }catch (\SQLiteException $ex){
                return false;
            }
        }
        $physioPatientOrm;
        //d($patientOrm);
        if($patientOrm && $patientOrm->physiotherapy){
            // physiotherapy exist
            $physioPatientOrm = PatientPhysiotherapy::where('id','=',$patientOrm->physiotherapy->id)->first();

        }else{
            // add new physiotherapy
            $physioPatientOrm = new PatientPhysiotherapy();
            $physioPatientOrm->patient_id = $patientOrm->id;
        }
        try{
            $physioPatientOrm->condition_id=$patientInfo['condition'];
            $physioPatientOrm->present_condition = $patientInfo['presentCondition'];
            $physioPatientOrm->save();
            return $patientOrm;
        }catch (\SQLiteException $ex){
            return false;
        }



    }

    public function updateTaskInfo($leadId,$taskInfo){

        $leadOrm = Lead::where('id','=',$leadId)->with('patient')->with('patient.tasks')->first();

        $patientOrm=null;
        $patientUpdated = false;
        $patientAilmentUpdated =false;
        if(!$leadOrm->patient){
            // no patient info available
            $patientOrm= new Patient();
            $patientOrm->lead_id=$leadId;
        }else{
            // update patient
            $patientOrm = Patient::where('id','=',$leadOrm->patient->id)->with('ailments')->first();
        }
        if($patientOrm){

            //TODO: more info to save
            $patientOrm->shift_id=$taskInfo['shift'];
            try{
                $patientOrm->save();
            }catch (\SQLiteException $ex){
                return false;
            }
        }
        if( isset($taskInfo['tasks']) && count($taskInfo['tasks'])>0){
            if($patientOrm && $patientOrm->tasks && $patientOrm->tasks->count()>0){
                // ailment exist

                $mappedArr = array();
                foreach($patientOrm->tasks as $tempAilment){
                    $mappedArr[$tempAilment->id]=true;
                }
                $toAddTask = array();
                foreach($taskInfo['tasks'] as $tempAil){
                    if(isset($mappedArr[$tempAil])){
                        $mappedArr[$tempAil]=false;
                    }else{
                        array_push($toAddTask,$tempAil);
                    }
                }
                foreach($mappedArr as $key=>$tempAil) {
                    if ($tempAil == true) {
                        // delete it
                        $patAIlOrm = PatientTask::whereRaw('patient_id = ? and task_id = ?', array($patientOrm->id, $key))->first();
                        $patAIlOrm->delete();
                    }
                }
                $toInsertArr = array();
                foreach($toAddTask as $tempAil){
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    $tempArr['task_id'] = $tempAil;
                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr,$tempArr);
                }
                PatientTask::insert($toInsertArr);
                return $patientOrm;
            }else{
                // add new ailments
                $toInsertArr = array();

                foreach($taskInfo['tasks'] as $tempAilment){
                    $tempArr = array();
                    $tempArr['patient_id'] = $patientOrm->id;
                    $tempArr['task_id'] = $tempAilment;
                    $tempArr['created_at'] = Carbon::now();
                    array_push($toInsertArr,$tempArr);
                }
                try{
                    PatientTask::insert($toInsertArr);
                    return $patientOrm;
                }catch (\SQLiteException $ex){
                    return false;
                }
            }
        }
        return $patientOrm;



    }

    public function addPriceToLead($leadId,$price,$priceUnit){
        $leadPrice = new LeadPrice();
        $leadPrice->lead_Id = $leadId;
        $leadPrice->price = $price;
        $leadPrice->price_unit_id = $priceUnit;
        $leadPrice->save();
    }

    public function updatePriceToLead($leadId,$price,$priceUnit){
        $leadPrice = new LeadPrice();
        $leadPrice->lead_Id = $leadId;
        $leadPrice->price = $price;
        $leadPrice->price_unit_id = $priceUnit;
        $leadPrice->save();
    }



    public function updatePatientValidationInfo($patientId,$validationOrm,$taskList){

        $patientValidationItem = PatientValidation::where('patient_id','=',$patientId)->with('tasks')->first();
        if(!$patientValidationItem){
            $patientValidationItem=new PatientValidation();
            $patientValidationItem->patient_id = $patientId;
            $patientValidationItem->save();
        }
        $patientInfo = Patient::where('id','=',$patientId)->with('tasks')->first();
        $alreadyTaskMapped = [];
        foreach($patientInfo->tasks as $taskItem){
            $alreadyTaskMapped[$taskItem->id]=true;
        }
        $toAddTaskList = array();
        foreach($taskList as $tempTask){
            //d($tempTask);
            if(isset($alreadyTaskMapped[$tempTask])&& $alreadyTaskMapped==true){
                $alreadyTaskMapped[$tempTask]=false;
            }else{
                array_push($toAddTaskList,array(
                    'patient_id'=>$patientId,
                    'task_id' =>$tempTask
                ));
            }
        }
        // removing task
       // d($alreadyTaskMapped);
        foreach($alreadyTaskMapped as $key=>$value){
            if($value==true){
                $patientTaskToDelete = PatientTask::whereRaw('patient_id = ? and task_id =?',array($patientId,$key))->first();
                $patientTaskToDelete->delete();
            }
        }
        // adding
        try{
            //d($toAddTaskList);
            PatientTask::insert($toAddTaskList);
            return $patientValidationItem->id;
        }catch (\SQLiteException $e){
            return false;
        }
    }

    public function getTaskCategoryWithTask(){
        return TaskCategory::with('tasks')->get();
    }

    public function patientValidationData($patientId){
        return PatientValidation::where('patient_id','=',$patientId)->with('tasks')->first();
    }

    public function updateLeadSpecialRequest($leadId,$requestInfo)
    {
        $leadOrm = Lead::where('id', '=', $leadId)->with('patient')->first();

        $patientOrm = null;
        $patientUpdated = false;
        $patientAilmentUpdated = false;

        $startDate = date("Y-m-d",strtotime($requestInfo['startDate']));
        if(isset($requestInfo['startTime'])){
            $startTime = date("h:i:s",strtotime($requestInfo['startTime']));
        }else{
            $startTime = '00:00:00';
        }

        $concat = ($startDate." ".$startTime);
        $leadOrm->start_date = $concat;
        if(isset($requestInfo['serviceDuration'])){
            $leadOrm->number_of_days = $requestInfo['serviceDuration'];
        }
        $leadOrm->save();

        if (!$leadOrm->patient) {
            // no patient info available
            $patientOrm = new Patient();
            $patientOrm->lead_id = $leadId;
        } else {
            // update patient
            $patientOrm = Patient::where('id', '=', $leadOrm->patient->id)->with('ailments')->first();
        }
        if ($patientOrm) {
            //TODO: more info to save
            $patientOrm->religion_preference = $requestInfo['religion'];
            $patientOrm->religion_preferred = $requestInfo['religionPreferred'];
            $patientOrm->gender_preference = $requestInfo['gender'];
            $patientOrm->gender_preferred = $requestInfo['genderPreferred'];
            $patientOrm->language_preference = $requestInfo['language'];
            $patientOrm->language_preferred = $requestInfo['languagePreferred'];
            $patientOrm->age_preference = $requestInfo['age'];
            $patientOrm->age_preferred = $requestInfo['agePreferred'];
            $patientOrm->food_preference = $requestInfo['food'];
            $patientOrm->food_preferred = $requestInfo['foodPreferred'];
            if(isset($requestInfo['otherAilment'])){
                $patientOrm->other_ailment = $requestInfo['otherAilment'];
            }
            try {
                $patientOrm->save();
                return $leadOrm;
            } catch (\SQLiteException $ex) {
                return false;
            }
        }
        return false;
    }
    public function updateLeadSpecialRequestCRM($leadId,$requestInfo)
    {
        $leadOrm = Lead::where('id', '=', $leadId)->with('patient')->first();

        $patientOrm = null;
        $patientUpdated = false;
        $patientAilmentUpdated = false;

        $leadOrm->start_date = date("Y-m-d h:i:s",strtotime($requestInfo['startDate']));
        if(isset($requestInfo['serviceDuration'])){
            $leadOrm->number_of_days = $requestInfo['serviceDuration'];
        }
        $leadOrm->save();

        if (!$leadOrm->patient) {
            // no patient info available
            $patientOrm = new Patient();
            $patientOrm->lead_id = $leadId;
        } else {
            // update patient
            $patientOrm = Patient::where('id', '=', $leadOrm->patient->id)->with('ailments')->first();
        }
        if ($patientOrm) {

            //TODO: more info to save
            $patientOrm->religion_preference = $requestInfo['religion'];
            if(isset($requestInfo['religionRequired']['id'])){
                $patientOrm->religion_preferred = $requestInfo['religionRequired']['id'];
            }else{
                $patientOrm->religion_preferred = null;
            }
            $patientOrm->gender_preference = $requestInfo['gender'];
            if(isset($requestInfo['genderRequired']['id'])){
                $patientOrm->gender_preferred = $requestInfo['genderRequired']['id'];
            }else{
                $patientOrm->gender_preferred =null;
            }
            $patientOrm->language_preference = $requestInfo['language'];
            if(isset($requestInfo['languageRequired']['id'])){
                $patientOrm->language_preferred = $requestInfo['languageRequired']['id'];
            }else{
                $patientOrm->language_preferred = null;
            }

            $patientOrm->age_preference = $requestInfo['age'];
            if(isset($requestInfo['ageRequired']['id'])){
                $patientOrm->age_preferred = $requestInfo['ageRequired']['id'];
            }else{
                $patientOrm->age_preferred = null;
            }
            $patientOrm->food_preference = $requestInfo['food'];
            if(isset($requestInfo['foodRequired']['id'])){
                $patientOrm->food_preferred = $requestInfo['foodRequired']['id'];
            }else{
                $patientOrm->food_preferred = null;
            }
            try {
                $patientOrm->save();
                return $leadOrm;
            } catch (\SQLiteException $ex) {
                return false;
            }
        }
        return false;
    }



    public function getExportData() {
        $tableData = Export::get();
        return $tableData;
    }

    public function getVendorData($id)
    {
        $data = UserVendor::where('user_id','=',$id)->with('User')->first();
        return $data;
    }

    public function uploadPrescription($file,$patientId=null){
        $prescriptionOrm = new Prescription();
        $prescriptionOrm->patient_id=$patientId;
        $prescriptionOrm->file_name = $file->getClientOriginalName();
        $prescriptionOrm->file_type = $file->getMimeType();
        $prescriptionOrm->save();

        $imageUploadPath = storage_path() . DIRECTORY_SEPARATOR;
        $imageUploadPath .= "app".DIRECTORY_SEPARATOR."prescriptions".DIRECTORY_SEPARATOR;
        if (! File::exists ( $imageUploadPath )) {
            File::makeDirectory ( $imageUploadPath, 0775, true, true );
        }

        if (! File::exists ( $imageUploadPath )) {
            File::makeDirectory ( $imageUploadPath, 0775, true, true );
        }


        try{
            if($file->move ( $imageUploadPath, $prescriptionOrm->id.".png" )){
                return $prescriptionOrm;
            }
        }catch (Exception $e){
            return false;
        }
    }


    public function deleteLead($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->delete();
        return $leadOrm;
    }
    public function deleteBulkLead($leadIdList){
        foreach ($leadIdList as $leadIds) {
            $leadOrm = Lead::whereIn('id', $leadIds)->delete();
        }
        return true;
    }
    public function getLeadPhoneNumber($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        return $leadOrm->phone;
    }

    public function isAuthorizedToViewContact($authUser){
        $userAuthorized = array('sachin@pramaticare.com','simran@pramaticare.com','kanishk@pramaticare.com','mayur@pramaticare.com','robin@pramaticare.com','vishal@pramaticare.com','nandkishore@pramaticare.com','aditya@pramaticare.com','anupkumar@pramaticare.com','richa@pramaticare.com','kajal@pramaticare.com','seema.chauhan@pramaticare.com','meenu@pramaticare.com','sumitraina@pramaticare.com');
        array_push($userAuthorized,"mohit2007gupta@gmail.com");


        if(in_array($authUser->email,$userAuthorized)){
            return true;
        }
        return false;
    }

    public function getLeadCurrentPrimaryVendor($leadId){
        return LeadVendor::whereRaw('lead_id = ? and is_primary = ?',array($leadId, true))->with('tasks')->with('tasks.task')->orderBy('created_at','desc')->first();;
    }
    public function getLeadCurrentBackUpVendor($leadId){
        return LeadVendor::whereRaw('lead_id = ? and is_primary = ?',array($leadId, false))->with('tasks')->with('tasks.task')->orderBy('created_at','desc')->first();;
    }

    public function getCurrentAssignedQc($leadId){
        return LeadQCEmployee::where('lead_id','=',$leadId)->with('briefingTasks')->with('briefingTasks.task')->orderBy('created_at','desc')->first();;
    }

    public function getCGEvaluationData($leadId,$isPrimary){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if($primaryVendorAssignment){
            return LeadVendorEvaluation::where('lead_vendor_id',$primaryVendorAssignment->id)->get();
        }
        return null;

    }


    public function getCGTrainingData($leadId,$isPrimary){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if($primaryVendorAssignment){
            return LeadVendorTrainingEvaluation::where('lead_vendor_id',$primaryVendorAssignment->id)->get();
        }
        return null;

    }

    public function getCGCustomerSignOffData($leadId,$isPrimary){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if($primaryVendorAssignment){
            return LeadVendorCustomerSignOff::where('lead_vendor_id',$primaryVendorAssignment->id)->get();
        }
        return null;

    }

    public function getLeadByCustomerPhone($phone){
        return Lead::where('phone','=',$phone)
            ->with('service')
            ->orderBy('created_at','desc')
            ->get();
    }

    public function getLeadClosureOptions(){
        return OperationalStatus::whereIn('slug',['closed','hold'])
            ->with('reasons')
            ->get();
    }

    public function markCustomerClosureRequest($leadId,$userId,$closureStatusId,$closureReasonId,$closureIssueId,$closureOtherReason){
        $customerStatusRequest = new CustomerStatusRequest();
        $customerStatusRequest->status_id = $closureStatusId;
        $customerStatusRequest->lead_id = $leadId;
        $customerStatusRequest->reason_id = $closureReasonId;
        $customerStatusRequest->issue_id = $closureIssueId;

        $customerStatusRequest->other_info = $closureOtherReason;

        try{
            $customerStatusRequest->save();
            return CustomerStatusRequest::where('id','=',$customerStatusRequest->id)->with('status')->with('reason')->with('issue')->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function submitVendorNotReachedStatus($leadId,$customerId,$vendorStatusKey, $comment){
        $customerStatusRequest = new CustomerVendorStatus();
        $customerStatusRequest->lead_id = $leadId;
        $customerStatusRequest->customer_id = $customerId;
        $customerStatusRequest->comment = $comment;
        $customerStatusRequest->vendor_status_key = $vendorStatusKey;


        try{
            $customerStatusRequest->save();
            return true;
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    public function submitLeadRestartRequestFromCustomer($customerId,$leadId){
        $operationStatus = OperationalStatus::where('slug','=','restart')->first();
        $customerStatusRequest = new CustomerStatusRequest();
        $customerStatusRequest->status_id = $operationStatus->id;
        $customerStatusRequest->lead_id = $leadId;
        $customerStatusRequest->user_id = $customerId;

        try{
            $customerStatusRequest->save();
            return CustomerStatusRequest::where('id','=',$customerStatusRequest->id)->with('status')->with('reason')->with('issue')->first();
        }catch (\SQLiteException $ex){
            return false;
        }
    }


    public function submitCustomerFeedback($customerId,$rating,$behaviourId,$comment){
        $customerFeedback = new CustomerFeedback();
        $customerFeedback->customer_id = $customerId;
        $customerFeedback->vendor_behaviour_id = $behaviourId;
        $customerFeedback->rating = $rating;
        $customerFeedback->comment = $comment;

        $customerFeedback->save();
        return $customerFeedback;
    }

    public function markCustomerCaregiverAttendance($leadId,$customerId,$attendanceDate, $isPresent,$isOnTime,$isWellDressed){
        $customerVendorAttendance = new CustomerVendorAttendance();
        $customerVendorAttendance->lead_id = $leadId;
        $customerVendorAttendance->is_present = $isPresent;
        $customerVendorAttendance->is_on_time = $isOnTime;
        $customerVendorAttendance->is_well_dressed = $isWellDressed;
        $customerVendorAttendance->customer_id = $customerId;
        $customerVendorAttendance->attendance_date	 = $attendanceDate;
        try{
            $customerVendorAttendance->save();
            return $customerVendorAttendance;
        }catch (\SQLiteException $ex){
            return false;
        }
    }

    // scheduler domain
    public function getNotNotifiedNewLeadFromGivenDate($timestamp){
        return Lead::where('created_at','>',$timestamp)
            ->with('prices.priceUnit')
            ->with('patient')
            ->with('userCreated')
            ->with('prices')
            ->with('patient.tasks')
            ->with('patient.genderItem')
            ->with('patient.shift')
            ->with('patient.agePreferred')
            ->with('patient.religionPreferred')
            ->with('patient.languagePreferred')
            ->with('patient.foodPreferred')
            ->with('patient.equipment')
            ->with('patient.ailments')
            ->with('patient.mobilityItem')
            ->with('employeesAssigned')
            ->with('vendorsAssigned')
            ->with('qcAssigned')
            ->with('statuses')
            ->with('leadReference')
            ->with('leadSource')
            ->with('paymentType')
            ->with('paymentPeriod')
            ->with('paymentMode')
            ->with('locality')
            ->with('service')
            ->with('leadReference')
            ->with('patientAilment')
            ->with('prices')->get();
    }

    public function getLeadCreatedInDuration($startTimeStamp,$endTimeStamp){
        return Lead::whereRaw('created_at > ? and created_at < ?', array($startTimeStamp,$endTimeStamp))
            ->with('prices.priceUnit')
            ->with('patient')
            ->with('userCreated')
            ->with('prices')
            ->with('patient.tasks')
            ->with('patient.genderItem')
            ->with('patient.shift')
            ->with('patient.agePreferred')
            ->with('patient.religionPreferred')
            ->with('patient.languagePreferred')
            ->with('patient.foodPreferred')
            ->with('patient.equipment')
            ->with('patient.ailments')
            ->with('patient.mobilityItem')
            ->with('employeesAssigned')
            ->with('vendorsAssigned')
            ->with('qcAssigned')
            ->with('statuses')
            ->with('leadReference')
            ->with('leadSource')
            ->with('paymentType')
            ->with('paymentPeriod')
            ->with('paymentMode')
            ->with('locality')
            ->with('service')
            ->with('leadReference')
            ->with('patientAilment')
            ->with('approvalEscalations')
            ->with('prices')->get();
    }
    public function sendSmsToCustomerAboutFieldAssignment($leadDetail){
        return $this->sendSMS($leadDetail->phone,urlencode("Dear Customer, this is to Confirm that your service will start at the scheduled time. An email with further details has been mailed to you. Kindly check for your reference. Thanks, Pramaticare."),13);
    }
    public function getPendingAssignmentNotification($startTimeStamp){
        return Lead::whereRaw('created_at > ?',array($startTimeStamp, null, null))
            ->with('prices.priceUnit')
            ->with('patient')
            ->with('userCreated')
            ->with('prices')
            ->with('patient.tasks')
            ->with('patient.genderItem')
            ->with('patient.shift')
            ->with('patient.agePreferred')
            ->with('patient.religionPreferred')
            ->with('patient.languagePreferred')
            ->with('patient.foodPreferred')
            ->with('patient.equipment')
            ->with('patient.ailments')
            ->with('patient.mobilityItem')
            ->with('employeesAssigned')
            ->with('vendorsAssigned')
            ->with('qcAssigned')
            ->with('fieldAssigned')
            ->with('statuses')
            ->with('leadReference')
            ->with('leadSource')
            ->with('paymentType')
            ->with('paymentPeriod')
            ->with('paymentMode')
            ->with('locality')
            ->with('service')
            ->with('leadReference')
            ->with('patientAilment')
            ->with('approvalEscalations')
            ->with('prices')->get();
    }

    public function markLeadApprovalEscalation($leadId,$delay){
        $leadApprovalEscalations = new LeadApprovalEscalation();
        $leadApprovalEscalations->lead_id = $leadId;
        $leadApprovalEscalations->delay=$delay;
        $leadApprovalEscalations->save();
    }

    public function markNewLeadNotificationSend($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->notification_sent = true;
        $leadOrm->save();

    }

    public function submitCarePlanPrimaryEvaluation($leadId,$isPrimary,$data, $userId){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if(!$primaryVendorAssignment){
            return false;
        }

        $toInsert = array();

        $alreadyExist = LeadVendorEvaluation::where('lead_vendor_id','=',$primaryVendorAssignment->id)->get();
        $taskMapper = array();

        foreach($alreadyExist as $tempAl){
            $taskMapper[$tempAl->task_id] = $tempAl;
        }

        foreach($data as $tempTask){
            $checked = (isset($tempTask['checked']) && $tempTask['checked']==true);
            if(isset($taskMapper[$tempTask['task_id']])){
                $taskMapper[$tempTask['task_id']]->evaluation = $checked;
                $taskMapper[$tempTask['task_id']]->save();
            }else{
                array_push($toInsert,array(
                    'lead_vendor_id'=>$primaryVendorAssignment->id,
                    'task_id'=>intval($tempTask['task_id']),
                    'evaluation'=>$checked,
                    'marked_by_user_id'=>$userId
                ));
            }
        }
        LeadVendorEvaluation::insert($toInsert);
        return true;
    }
    public function submitCarePlanTrainingEvaluation($leadId,$isPrimary,$data,$userId){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if(!$primaryVendorAssignment){
            return false;
        }

        $toInsert = array();

        $alreadyExist = LeadVendorTrainingEvaluation::where('lead_vendor_id','=',$primaryVendorAssignment->id)->get();
        $taskMapper = array();

        foreach($alreadyExist as $tempAl){
            $taskMapper[$tempAl->task_id] = $tempAl;
        }

        foreach($data as $tempTask){
            $checked = (isset($tempTask['checked']) && $tempTask['checked']==true);
            if(isset($taskMapper[$tempTask['task_id']])){
                $taskMapper[$tempTask['task_id']]->training = $checked;
                $taskMapper[$tempTask['task_id']]->save();
            }else{
                array_push($toInsert,array(
                    'lead_vendor_id'=>$primaryVendorAssignment->id,
                    'task_id'=>intval($tempTask['task_id']),
                    'training'=>$checked,
                    'marked_by_user_id'=>$userId
                ));
            }
        }
        LeadVendorTrainingEvaluation::insert($toInsert);
        return true;
    }
    public function submitCarePlanCustomerSignOff($leadId,$isPrimary,$data,$userId){
        $primaryVendorAssignment = LeadVendor::whereRaw('lead_id = ? and is_primary = ?', array($leadId,$isPrimary))
            ->orderBy('created_at','desc')
            ->first();
        if(!$primaryVendorAssignment){
            return false;
        }

        $toInsert = array();

        $alreadyExist = LeadVendorCustomerSignOff::where('lead_vendor_id','=',$primaryVendorAssignment->id)->get();
        $taskMapper = array();

        foreach($alreadyExist as $tempAl){
            $taskMapper[$tempAl->task_id] = $tempAl;
        }

        foreach($data as $tempTask){
            $checked = (isset($tempTask['checked']) && $tempTask['checked']==true);
            if(isset($taskMapper[$tempTask['task_id']])){
                $taskMapper[$tempTask['task_id']]->sign_off = $checked;
                $taskMapper[$tempTask['task_id']]->save();
            }else{
                array_push($toInsert,array(
                    'lead_vendor_id'=>$primaryVendorAssignment->id,
                    'task_id'=>intval($tempTask['task_id']),
                    'sign_off'=>$checked,
                    'marked_by_user_id'=>$userId
                ));
            }
        }
        LeadVendorCustomerSignOff::insert($toInsert);
        return true;
    }

    public function submitCGAttendance($leadId,$vendorId,$attendanceDate,$attendance,$vendorPrice,$comment,$userId,$medium=""){

        $leadVendorAttendance = new LeadVendorAttendance();

        $leadVendorAttendance->lead_id = $leadId;
        $leadVendorAttendance->date = $attendanceDate;
        $leadVendorAttendance->vendor_id = $vendorId;
        $leadVendorAttendance->is_present = $attendance;
        $leadVendorAttendance->vendor_price = $vendorPrice;
        $leadVendorAttendance->comment = $comment;
        $leadVendorAttendance->marked_by = $userId;
        $leadVendorAttendance->medium = $medium;

        try{
            $leadVendorAttendance->save();
            return true;
        }catch (\SQLiteException $ex){
            return false;
        }

    }

    public function markCallInitiationMailSend($leadDetail){
        $leadOrm = Lead::where('id','=',$leadDetail->id)->first();
        $leadOrm->call_initiation_mail_send_at = Carbon::now();
        $leadOrm->save();




    }

    public function getStartedLead(){
        return LeadStatus::where('status_id','=',$this->getStatusBySlug('started')->id)->orderBy('created_at','asc')->get();
    }

    public function getActiveLeadOnDate($date){
        return LeadActiveDate::where('active_Date','=',$date)->groupBy('lead_id')->get();
    }
    
    public function getVendorAttendanceOnDate($date){

        $activeRecordOrm = new LeadActiveDate();
        $customerVendorAttendance = new CustomerVendorAttendance();
        $vendorAttendance = new LeadVendorAttendance();
        $customerAttendanceList = CustomerVendorAttendance::where('attendance_date','=',$date)->get();
        $vendorAttendanceList = LeadVendorAttendance::where('date','=',$date)->get();

        $vendorIncentivesList = Incentives::where('date','=',$date)->get();
        
        $attendanceMapper = array();

        foreach($customerAttendanceList as $tempAtt){
            if(!isset($attendanceMapper[$tempAtt->lead_id])){
                $attendanceMapper[$tempAtt->lead_id] = array();
                $attendanceMapper[$tempAtt->lead_id]['customer'] = array();
                $attendanceMapper[$tempAtt->lead_id]['crm'] = array();
                $attendanceMapper[$tempAtt->lead_id]['vendorIncentive'] = array();
            }
            $attendanceMapper[$tempAtt->lead_id]['customer'] = $tempAtt;
        }



        foreach($vendorAttendanceList as $tempAtt){
            if(!isset($attendanceMapper[$tempAtt->lead_id])){
                $attendanceMapper[$tempAtt->lead_id] = array();
                $attendanceMapper[$tempAtt->lead_id]['customer'] = array();
                $attendanceMapper[$tempAtt->lead_id]['crm'] = array();
                $attendanceMapper[$tempAtt->lead_id]['vendorIncentive'] = array();
            }
            $attendanceMapper[$tempAtt->lead_id]['crm'] = $tempAtt;
        }

        if(!empty($vendorIncentivesList)){
            foreach($vendorIncentivesList as $tempAtt){

                if(!isset($attendanceMapper[$tempAtt->lead_id])){
                    $attendanceMapper[$tempAtt->lead_id] = array();
                    $attendanceMapper[$tempAtt->lead_id]['customer'] = array();
                    $attendanceMapper[$tempAtt->lead_id]['crm'] = array();
                    $attendanceMapper[$tempAtt->lead_id]['vendorIncentive'] = array();
                }
                $attendanceMapper[$tempAtt->lead_id]['vendorIncentive'] = $tempAtt;
            }
        }

        return ($attendanceMapper);

        $sqlStringForCustomer = "select * from ".$activeRecordOrm->getTable()." as a, ".$customerVendorAttendance->getTable()." as c where a.active_date = '".$date."' and a.lead_id = c.lead_id and c.attendance_date = '".$date."' ";
        $sqlStringForVendor = "select * from ".$activeRecordOrm->getTable()." as a,  ".$vendorAttendance->getTable()." as v  where a.active_date = '".$date."' and a.lead_id = v.lead_id and v.date= '".$date."'  ";
        die();
    }


    public function checkAndMarkActiveDate($leadId,$date){

        $checkExist = LeadActiveDate::whereRaw('lead_id = ? and active_date = ?', array($leadId,$date))->get();

        if($checkExist->count()>0){
            return true;
        }
        $leadActiveDate = new LeadActiveDate();
        $leadActiveDate->lead_id = $leadId;
        $leadActiveDate->active_date = $date;
        $leadActiveDate->save();
        return true;
    }

    public function getActiveLeads(){
        $startedStatus = OperationalStatus::where('slug','=','started')->first();
        $startedStatusId = $startedStatus->id;
        $allLead = Lead::with('statuses')->get();
        $mapperArr = [];
        foreach($allLead as $tempLead){
            if(!$tempLead->statuses || !$tempLead->statuses->last()){
                continue;
            }
            $lastStatus = $tempLead->statuses->last();
            if($lastStatus->id == $startedStatusId){
                array_push($mapperArr,$tempLead->id);
            }
        }
        return Lead::whereIn('id',$mapperArr)
            ->with('prices.priceUnit')
            ->with('patient')
            ->with('userCreated')
            ->with('prices')
            ->with('patient.tasks')
            ->with('patient.genderItem')
            ->with('patient.shift')
            ->with('patient.agePreferred')
            ->with('patient.religionPreferred')
            ->with('patient.languagePreferred')
            ->with('patient.foodPreferred')
            ->with('patient.equipment')
            ->with('patient.ailments')
            ->with('patient.mobilityItem')
            ->with('employeesAssigned')
            //->with('vendorsAssigned')
            ->with('primaryVendorsAssigned')
            ->with('qcAssigned')
            ->with('statuses')
            ->with('leadReference')
            ->with('leadSource')
            ->with('paymentType')
            ->with('paymentPeriod')
            ->with('paymentMode')
            ->with('locality')
            ->with('service')
            ->with('leadReference')
            ->with('patientAilment')
            ->with('prices')->get();
    }

    public function getAllSlackedLead(){
        return Lead::whereNotNull('slack_channel_created_at')->get();

    }

    public function updateSlackChannelIdForLead($leadId,$slackChannelId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->slack_channel_id = $slackChannelId;
        $leadOrm->save();
    }

    public function checkAndGenerateSlackChannelForLead($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->with('statuses')->first();

        $leadStatus = null;

        if($leadOrm->statuses && $leadOrm->statuses->count() && $leadOrm->statuses->last()){
            $leadStatus = $leadOrm->statuses->last();
        }else{
            return "";
        }

        $possibleStatusOfLead = ['lead-approved','approved','started','active','restart','validated','follow-up'];

        if($leadStatus==null || !in_array($leadStatus->slug,$possibleStatusOfLead)){
            return "";
        }

        if($leadOrm->slack_channel_name){
            return $leadOrm->slack_channel_name;
        }
        $generatedSlackChannelName = strtolower(str_replace(' ','_',trim($leadOrm->customer_name."_".$leadOrm->customer_last_name)));
        $channelName = $generatedSlackChannelName;
        // check if already present
        for($i=1; true;$i++){
            $alreadyPresentCount = Lead::where('slack_channel_name','=',$channelName)->count();
            if($alreadyPresentCount==0){
                break;
            }
            $channelName = $generatedSlackChannelName."_".$i;
        }
        $leadOrm->slack_channel_name = $channelName;
        $leadOrm->slack_channel_created_at = Carbon::now();
        $leadOrm->save();


        return $channelName;
    }

    public function addWatchersToLead($leadId,$watcherList){
        foreach($watcherList as $userId){
            $checkWatcher = LeadWatcher::whereRaw('lead_id = ? and user_id = ?',array($leadId,$userId))->count();
            if($checkWatcher>0){
                continue;
            }
            $leadWatcher = new LeadWatcher();
            $leadWatcher->lead_id = $leadId;
            $leadWatcher->user_id = $userId;
            $leadWatcher->save();
        }
    }


    public function addWatchersToLeadByEmail($leadId,$watcherEmailList){
        
    }

    public function getPendingWatcherInvitationForSlack(){
        $allWatchers = LeadWatcher::whereNull('slack_invitation_sent_at')
            ->with('user')
            ->with('lead')
            ->with('user.employeeInfo')
            ->get();
        return $allWatchers;
    }


    public function markAndSendSlackInvitationForWatcher($leadWatcherId){
        $leadWatcher = LeadWatcher::where('id','=',$leadWatcherId)
            ->with('user')
            ->with('lead')
            ->with('user.employeeInfo')
            ->first();
        if(!$leadWatcher || !$leadWatcher->lead){
            return;
        }
        Log::info('$leadWatcher->lead->slack_channel_id -> '.$leadWatcher->lead->slack_channel_id);
        Log::info('$leadWatcher->user->employeeInfo->slack_user_id -> '.$leadWatcher->user->employeeInfo->slack_user_id);
        $slackChannelId = $leadWatcher->lead->slack_channel_id;
        $slackUserId = $leadWatcher->user->employeeInfo->slack_user_id;
        //Log::info($slackChannelId);
        //Log::info($slackUserId);
        Log::info('$leadWatcher->lead->slack_channel_id -> '.$slackChannelId);
        Log::info('$leadWatcher->user->employeeInfo->slack_user_id -> '.$slackUserId);
        Log::info(var_dump($slackUserId));

       $resp =  SlackChannel::invite(trim($slackChannelId),trim($slackUserId));
        if($resp->ok==true){
            $leadWatcher->slack_invitation_sent_at = Carbon::now();
        }

        $leadWatcher->save();
    }

    public function getStatusChangeRequestByCustomer($leadId){
        return CustomerStatusRequest::where('lead_id','=',$leadId)->with('status')->with('reason')->with('issue')->get();
    }

    public function getCustomerNotifications($leadId){
        return CustomerNotification::where('lead_id','=',$leadId)->with('user')->get();
    }
    public function getVendorAttendanceByCustomer($leadId){
        return CustomerVendorAttendance::where('lead_id','=',$leadId)->get();
    }
    public function getVendorStatusByCustomer($leadId){
        return CustomerVendorStatus::where('lead_id','=',$leadId)->get();
    }



    public function getPendingCGAssignmentNotification(){
        return Lead::whereNull('cg_assigned_notification_sent_at')->whereNotNull('cg_assigned_at')->get();
    }
    public function getPendingQCAssignmentNotification(){
        return Lead::whereNull('qc_assigned_notification_sent_at')->whereNotNull('qc_assigned_at')->get();
    }
    public function getPendingLeadStartedNotification(){
        return Lead::whereNull('start_service_mail_sent_at')->whereNotNull('started_service_at')->get();
    }
    public function markCgAssignmentNotificationSend($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->cg_assigned_notification_sent_at = Carbon::now();
        $leadOrm->save();
    }
    public function markQCAssignmentNotificationSend($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->qc_assigned_notification_sent_at = Carbon::now();
        $leadOrm->save();
    }
    public function markLeadStartedNotificationSend($leadId){
        $leadOrm = Lead::where('id','=',$leadId)->first();
        $leadOrm->start_service_mail_sent_at = Carbon::now();
        $leadOrm->save();
    }

    public function getLeadIdByVendorId($vendorId){
        return LeadVendor::where('assignee_user_id','=',$vendorId)
            ->orderBy('id','DESC')
            ->first();
    }

    public function getLatestVendorByLead($leadId){
        return LeadVendor::where('lead_id','=',$leadId)
            ->join('users', 'users.id', '=', 'lead_vendors.assignee_user_id')
            ->whereNull('lead_vendors.deleted_at')
            ->orderBy('lead_vendors.id','DESC')
            ->first();
    }

    public function getLatestPrimaryVendorByLead($leadId){
        return LeadVendor::where('lead_id','=',$leadId)
            ->join('users', 'users.id', '=', 'lead_vendors.assignee_user_id')
            ->where('is_primary','=',true)
            ->whereNull('lead_vendors.deleted_at')
            ->orderBy('lead_vendors.id','DESC')
            ->first();
    }

    public function getLeadIdByCustomerId($userId){
        return Lead::where('user_id','=',$userId)
            ->orderBy('id','DESC')
            ->first();
    }

    public function getAllotableEmployeesComplaintsCollection(){
        return UserType::where('slug','=','employee')
            ->with('users')
            ->first();
    }

    public function getLastEmployeeAssigned($leadId){
        return LeadEmployee::where('lead_id','=',$leadId)
            ->join('users', 'users.id', '=', 'lead_employees.assignee_user_id')
            ->orderBy('lead_employees.id','DESC')
            ->first();
    }

    public function getPresentCount($vendorId){
        $vendorAttendance = new LeadVendorAttendance();
        return LeadVendorAttendance::where('vendor_id','=',$vendorId)->where('is_present','=',1)->count();
    }

    public function getWorkingDaysCount($vendorId){
        $workingDays = 0;
        $usedLeads = array();
        $leadRangeDates = array();
        $leadRangeDatesVendor = array();
        $mapper = array();

        $vendorLeads = $this->getLeadsForVendor($vendorId);
        if(!empty($vendorLeads)){
            
            foreach ($vendorLeads as $vendorLead) {
                if(!in_array($vendorLead->lead_id, $usedLeads)){
                    $usedLeads[] = $vendorLead->lead_id;

                    $minDate = '';
                    $maxDate = '';

                    $leadActiveDates = LeadActiveDate::where('lead_id','=',$vendorLead->lead_id)->get();

                    if(count($leadActiveDates) != 0){
                        $lastActiveDate = $leadActiveDates->last()->active_date;


                        $leadVendors = $this->getVendorsForLead($vendorLead->lead_id);
                        if(!empty($leadVendors)){
                            
                            
                            //print_r($leadVendors[0]);

                            //$count = 0;
                            $tempFlag = false;

                            for($count = 1; $count <= count($leadVendors); $count ++){
                                
                                //$dt = Carbon::parse($leadVendors[$count]->created_at);
                                //$currentDate = $dt->toDateString();

                                /*if($vendorLead->lead_id == 912){
                                    print_r($leadVendors[1]->created_at->toDateString());
                                }*/

                                $minDate = Carbon::parse(Carbon::parse($leadVendors[$count - 1]->created_at)->toDateString())->timestamp;

                                if(isset($leadVendors[$count])){
                                    $maxDate = Carbon::parse(Carbon::parse($leadVendors[$count]->created_at)->subDay()->toDateString())->timestamp;
                                } else {
                                    $maxDate = Carbon::parse(Carbon::parse($lastActiveDate)->toDateString())->timestamp;
                                }

                                // if($count == 2){
                                //     echo $minDate.' '.$maxDate;
                                // }

                                if ($maxDate >= $minDate){
                                    array_push($leadRangeDates,date('Y-m-d',$minDate)); // first entry
                                    array_push($leadRangeDatesVendor,$leadVendors[$count - 1]->assignee_user_id);

                                    while ($minDate < $maxDate)
                                    {
                                        $minDate+=86400; // add 24 hours
                                        array_push($leadRangeDates,date('Y-m-d',$minDate));
                                        array_push($leadRangeDatesVendor,$leadVendors[$count - 1]->assignee_user_id);
                                    }
                                }

                            }

                            /*while ($count < count($leadVendors)) {
                                
                                $dt = Carbon::parse($leadVendors[$count]->created_at);
                                $currentDate = $dt->toDateString();

                                if($minDate == ''){
                                    $minDate = $dt->timestamp;
                                } else {
                                    $maxDate = $dt->subDay()->timestamp;

                                    if ($maxDate >= $minDate){
                                        array_push($leadRangeDates,date('Y-m-d',$minDate)); // first entry
                                        array_push($leadRangeDatesVendor,$leadVendors[$count]->assignee_user_id);

                                        while ($minDate < $maxDate)
                                        {
                                            $minDate+=86400; // add 24 hours
                                            array_push($leadRangeDates,date('Y-m-d',$minDate));
                                            array_push($leadRangeDatesVendor,$leadVendors[$count]->assignee_user_id);
                                        }
                                    }
                                }

                                if(!$tempFlag){
                                    $tempFlag = true;
                                    $count++;
                                    echo $count;
                                } else {
                                    $tempFlag = false;
                                    $minDate = '';
                                    $count--;
                                    echo $count;
                                    exit;
                                }

                            }*/

                        }

                        for($i=0;$i<count($leadRangeDates);$i++) {
                            $mapper[$leadRangeDates[$i]] = $leadRangeDatesVendor[$i];
                        }
                        //print_r($mapper);echo '<br>';
                        
                        //print_r($leadRangeDates);echo '<br>';
                        //print_r($leadRangeDatesVendor);
                        if(!empty($leadActiveDates)){
                            foreach ($leadActiveDates as $leadActiveDate) {
                                //echo $leadActiveDate->active_date.'<br>';
                                if(isset($mapper[$leadActiveDate->active_date])){
                                    if(($mapper[$leadActiveDate->active_date]) == $vendorId){
                                        $workingDays++;
                                    }
                                }
                            }
                        }

                    }

                    //exit;
                    //echo '<br>============================<br>';
                }
            }
        }

        return $workingDays;
    }

    public function getLeadsForVendor($vendorId){
        return LeadVendor::where('assignee_user_id','=',$vendorId)->where('is_primary','=',1)->get();
    }

    public function getVendorsForLead($leadId){
        return LeadVendor::where('lead_id','=',$leadId)->where('is_primary','=',1)->get();
    }

    public function getVendorComplaintCountByType($vendorId, $complaintTypeId){
        $childs = $this->getComplaintSubCategories($complaintTypeId);
        $query = Complaint::where('vendor_id', $vendorId);
        
        if(!empty($childs)){
            $query->where(function($query) use ($childs, $complaintTypeId){
                $query->orWhere('complaint_category', $complaintTypeId);
                foreach ($childs as $child) {
                    $query->orWhere('complaint_category', $child['id']);
                }
            });
        } else {
            $query->where('complaint_category', $complaintTypeId);
        }

        return $query->count();
    }

    public function getVendorComplaintCount($vendorId){
        return Complaint::where('vendor_id', $vendorId)->count();
    }

    public function getComplaintSubCategories($complaintCategoryId){
        $data = ComplaintsCategories::where('parent_id', $complaintCategoryId)->get();

        $dataArray = array();
        if(!empty($data)){
            $count = 0;
            foreach ($data as $row) {
                $dataArray[$count]['id'] = $row->id;
                $dataArray[$count]['name'] = $row->name;

                $dataInner = ComplaintsCategories::where('parent_id', $row->id)->get();
                if(!empty($dataInner)){
                    foreach ($dataInner as $rowInner) {
                        $dataArray[$count]['id'] = $rowInner->id;
                        $dataArray[$count]['name'] = $rowInner->name;
                        $count++;
                    }
                }
                $count++;
            }
        }
        return $dataArray;
    }

    public function vendor_rating_save(){
        $vendorDomainService = new VendorDomainService();
        $vendors = $vendorDomainService->getVendorList();
        
        foreach ($vendors as $vendor) {
            $vendorId = $vendor->user_id;

            $workingDaysCount = 0;
            $presentCount = 0;
            $presentPercent = 0;
            $absentCount = 0;
            $absentPercent = 0;
            $uninformedAbsents = 0;
            $uninformedAbsentsPercent = 0;


            $workingDaysCount = $this->getWorkingDaysCount($vendorId);
            
            // to prevent division by zero
            if($workingDaysCount != 0){
                $presentCount = $this->getPresentCount($vendorId);
                $presentPercent = $this->percentage($presentCount,$workingDaysCount);

                $absentCount = $workingDaysCount - $presentCount;
                $absentPercent = $this->percentage($absentCount,$workingDaysCount);

                $uninformedAbsents = $this->getVendorComplaintCountByType($vendorId, 75);
                $uninformedAbsentsPercent = $this->percentage($uninformedAbsents,$absentCount);
            }

            $punctualityComplaintCount = $this->getVendorComplaintCountByType($vendorId, 26);

            $prePlacementComplaintsCount = $this->getVendorComplaintCountByType($vendorId, 9);


            // save/update to db
            $vendorRating = VendorsRating::firstOrNew(array('user_id' => $vendorId));
            $vendorRating->user_id = $vendorId;
            $vendorRating->working_days = $workingDaysCount;
            $vendorRating->present_days = $presentCount;
            $vendorRating->present_percent = $presentPercent;
            $vendorRating->absent_days = $absentCount;
            $vendorRating->absent_percent = $absentPercent;
            $vendorRating->uninformed_absents = $uninformedAbsents;
            $vendorRating->uninformed_absents_percent = $uninformedAbsentsPercent;
            $vendorRating->punctuality_complaints = $punctualityComplaintCount;
            $vendorRating->pre_placement_complaints = $prePlacementComplaintsCount;

            // new code

            $skillsComplaintCount = 0;
            $skillsComplaintCountPercent = 0;
            $totalComplaints = 0;
            $complaintsRatio = 0;
            $crmAttendanceCount = 0;
            $crmAttendanceCountPercent = 0;
            $replacementRequestsCount = 0;
            $replacementRequestsCountPercent = 0;
            $leadAcceptance = 0;
            $leadAcceptancePercent = 0;
            $prePlacementComplaintsCountPercent = 0;
            $dependabilityPercent = 0;

            
            $totalComplaints = $this->getVendorComplaintCount($vendorId);

            if($workingDaysCount != 0){
                $skillsComplaintCount = $this->getVendorComplaintCountByType($vendorId, 18);
                $skillsComplaintCountPercent = $this->percentage($skillsComplaintCount,$workingDaysCount);

                $prePlacementComplaintsCountPercent = $this->percentage($prePlacementComplaintsCount,$workingDaysCount);
                $complaintsRatio = $this->percentage(($totalComplaints - ($uninformedAbsents+$punctualityComplaintCount+$prePlacementComplaintsCount+$skillsComplaintCount)),$workingDaysCount);
            
                $replacementRequestsCount = $this->getReplacementRequestsByVendorCount($vendorId);
                $replacementRequestsCountPercent = $this->percentage($replacementRequestsCount,$workingDaysCount);
            }

            if($presentCount != 0){
                $crmAttendanceCount = $this->getVendorAttendanceByMediumCount($vendorId,'CRM');
                $crmAttendanceCountPercent = $this->percentage($crmAttendanceCount,$presentCount);
            }
            
            $vendorRating->skills_complaints = $skillsComplaintCount;
            $vendorRating->skills_complaints_percent = $skillsComplaintCountPercent;
            $vendorRating->total_complaints = $totalComplaints;
            $vendorRating->complaints_ratio = $complaintsRatio;
            $vendorRating->crm_attendance = $crmAttendanceCount;
            $vendorRating->crm_attendance_percent = $crmAttendanceCountPercent;
            $vendorRating->replacement_requests = $replacementRequestsCount;
            $vendorRating->replacement_requests_percent = $replacementRequestsCountPercent;

            $vendorRating->lead_acceptance = $leadAcceptance;
            $vendorRating->lead_acceptance_percent = $leadAcceptancePercent;

            // final rating
                //scoring
                    // acceptance -> 2
                    // absent % -> 3
                    // uninformed * -> 3
                    // complaints ratio -> 3 
                    // pre preacement % -> 2
                    // crm attendance % -> 3
                    // replacemnt requests % -> 4

            $leadRejectPercent = 100 - $leadAcceptancePercent;
            $dependabilityPercent = ( ( ($leadRejectPercent*2) + ($absentPercent*3) + ($uninformedAbsentsPercent*3) + ($complaintsRatio*3) + ($prePlacementComplaintsCountPercent*2) + ($crmAttendanceCountPercent*3) + ($replacementRequestsCountPercent*4) ) / 20);

            $vendorRating->total_dependability_percent = 100 - $dependabilityPercent;
            $vendorRating->total_skills_percent = 100 - $skillsComplaintCountPercent;

            $vendorRating->save();

        }
    }

    public function percentage($value, $total){
        if($total == 0.0){
            return 0.00;
        } else {
            return ($value/$total) * 100;
        }
    }

    public function getVendorServiceCount($vendorId){
        return LeadVendor::where('assignee_user_id','=',$vendorId)->count();
    }

    public function getVendorAttendanceByMediumCount($vendorId,$medium){
        return LeadVendorAttendance::where('vendor_id','=',$vendorId)
            ->where('medium','like',$medium)
            ->count();
    }

    public function getReplacementRequestsByVendorCount($vendorId){
        return CgReplacement::where('user_id','=',$vendorId)->count();
    }

    public function getSalaryByPeriodReportCGWise($dateFrom,$dateTo,$difference){
        $return = array();
        $mainCount = 0;
        $usedVendorLead = array();
        $usedVendorLeadDates = array();
        $currentVendorId = 0;

        $leadVendorAttendanceList = LeadVendorAttendance::with('vendor')
            ->with('user')
            ->where('is_present','=',1)
            ->where('date','>=',$dateFrom)
            ->where('date','<=',$dateTo)
            ->orderBy('vendor_id','ASC')
            ->get();

        if(!empty($leadVendorAttendanceList)){
            $leadCounter = 0;
            foreach ($leadVendorAttendanceList as $leadVendorAttendance) {

                $vendorId = $leadVendorAttendance->vendor_id;
                $leadId = $leadVendorAttendance->lead_id;

                if($vendorId > $currentVendorId){
                    $return[$mainCount]['vendorInfo'] = $this->getVendorData($vendorId);
                    $return[$mainCount]['vendorId'] = $vendorId;
                    $currentVendorId = $vendorId;
                    $mainCount++;
                    $leadCounter = 0;
                }

                if(!in_array($vendorId.'_'.$leadId, $usedVendorLead)){
                    $usedVendorLead[] = $vendorId.'_'.$leadId;

                    $leadOrm = Lead::where('id','=',$leadId)->first();
                    $perDayPrice = LeadVendor::select('price_per_day')->where('lead_id','=',$leadId)->where('assignee_user_id','=',$vendorId)->where('is_primary','=',1)->first();
                    
                    if($leadOrm){
                        //$return[$mainCount-1]['leads'][$leadCounter]['leadInfo'] = $leadOrm;
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo']['customerName'] = $leadOrm->customer_name;
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo']['leadId'] = $leadId;
                    } else {
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo'] = null;
                    }
                    
                    $return[$mainCount-1]['leads'][$leadCounter]['pricePerDay'] = $perDayPrice['price_per_day'] ? $perDayPrice['price_per_day'] : 0;

                    $incentive = 0;
                    $deduction = 0;
                    $vendorIncentivesList = Incentives::where('user_id','=',$vendorId)->where('lead_id','=',$leadId)->get();

                    $vendorIncentiveUsedArray = array();
                    if(!empty($vendorIncentivesList)){
                        foreach ($vendorIncentivesList as $vendorIncentive) {

                            $dupIncString = $vendorIncentive->user_id.'_'.$vendorIncentive->lead_id.'_'.$vendorIncentive->date.'_'.$vendorIncentive->amount.'_'.$vendorIncentive->type;
                            if(!in_array($dupIncString, $vendorIncentiveUsedArray)){
                                if($vendorIncentive->amount > 0){
                                    $incentive += $vendorIncentive->amount;
                                } else if($vendorIncentive->amount < 0){
                                    $deduction -= $vendorIncentive->amount;
                                }
                                $vendorIncentiveUsedArray[] = $dupIncString;
                            }
                        }
                    }
                    $return[$mainCount-1]['leads'][$leadCounter]['incentive'] = $incentive;
                    $return[$mainCount-1]['leads'][$leadCounter]['deduction'] = $deduction;

                    $presentDays = 1;
                    if($presentDays > $difference){
                        $return[$mainCount-1]['leads'][$leadCounter]['presentDays'] = $difference+1;
                    } else {
                        $return[$mainCount-1]['leads'][$leadCounter]['presentDays'] = $presentDays;
                    }
                    $presentDays++;

                    $leadCounter++;                    
                } else {

                    if(!in_array($vendorId.'_'.$leadId.'_'.$leadVendorAttendance->date, $usedVendorLeadDates)){
                        $usedVendorLeadDates[] = $vendorId.'_'.$leadId.'_'.$leadVendorAttendance->date;

                        if($presentDays > $difference){
                            $return[$mainCount-1]['leads'][$leadCounter-1]['presentDays'] = $difference+1;
                        } else {
                            $return[$mainCount-1]['leads'][$leadCounter-1]['presentDays'] = $presentDays;
                        }
                        $presentDays++;
                    }
                    }

                }


            }
               
        return $return;
    }

    public function getSalaryByPeriodReportCustomerWise($dateFrom,$dateTo){
        $return = array();
        $mainCount = 0;
        $usedVendorLead = array();
        $currentVendorId = 0;

        //$activeLeads = LeadActiveDate::select(\DB::raw('count(*) as activeDatesCount, lead_id, leads.*'))->where('active_date','>=',$dateFrom)->where('active_date','<=',$dateTo)->groupBy('lead_id')->with('lead')->get();
        //$activeLeads = \DB::raw("SELECT count(LAD.id) as activeDatesCount, LAD.lead_id, L.* FROM lead_active_dates LAD, leads L where LAD.active_date >= '".$dateFrom."' AND LAD.active_date <= '".$dateTo."' AND LAD.deleted_at is null GROUP BY LAD.lead_id");
        //$activeLeads = \DB::raw("SELECT count(LAD.id) as activeDatesCount, LAD.lead_id, L.customer_name FROM lead_active_dates LAD, leads L where LAD.active_date >= '".$dateFrom."' AND LAD.active_date <= '".$dateTo."' AND LAD.deleted_at is null GROUP BY LAD.lead_id, L.customer_name");

        $activeLeads = LeadActiveDate::where('active_date','>=',$dateFrom)->where('active_date','<=',$dateTo)->groupBy('lead_id')->with('lead')->get();

        if(!empty($activeLeads)){
            foreach ($activeLeads as $activeLead) {
                $leadId = $activeLead->lead_id;

                if($activeLead->lead){
                    $customerId = $activeLead->lead->user_id;

                    $return[$mainCount]['leadId'] = $leadId;
                    $return[$mainCount]['customerId'] = $customerId;
                    $return[$mainCount]['customerName'] = $activeLead->lead->customer_last_name; 

                    $mainCount++;   
                }
            }
        }

        return $return;



                    /*if(count($leadActiveDates) != 0){
                        $lastActiveDate = $leadActiveDates->last()->active_date;*/




        $leadVendorAttendanceList = LeadVendorAttendance::with('vendor')
            ->with('user')
            ->where('date','>=',$dateFrom)
            ->where('date','<=',$dateTo)
            ->orderBy('vendor_id','ASC')
            ->get();

        if(!empty($leadVendorAttendanceList)){
            $leadCounter = 0;
            foreach ($leadVendorAttendanceList as $leadVendorAttendance) {

                $vendorId = $leadVendorAttendance->vendor_id;
                $leadId = $leadVendorAttendance->lead_id;

                if($vendorId > $currentVendorId){
                    $return[$mainCount]['vendorInfo'] = $this->getVendorData($vendorId);
                    $return[$mainCount]['vendorId'] = $vendorId;
                    $currentVendorId = $vendorId;
                    $mainCount++;
                    $leadCounter = 0;
                }

                if(!in_array($vendorId.'_'.$leadId, $usedVendorLead)){
                    $usedVendorLead[] = $vendorId.'_'.$leadId;

                    $leadOrm = Lead::where('id','=',$leadId)->first();
                    $perDayPrice = LeadVendor::select('price_per_day')->where('lead_id','=',$leadId)->where('assignee_user_id','=',$vendorId)->where('is_primary','=',1)->first();
                    
                    if($leadOrm){
                        //$return[$mainCount-1]['leads'][$leadCounter]['leadInfo'] = $leadOrm;
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo']['customerName'] = $leadOrm->customer_name;
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo']['leadId'] = $leadId;
                    } else {
                        $return[$mainCount-1]['leads'][$leadCounter]['leadInfo'] = null;
                    }
                    
                    $return[$mainCount-1]['leads'][$leadCounter]['pricePerDay'] = $perDayPrice['price_per_day'] ? $perDayPrice['price_per_day'] : 0;

                    $incentive = 0;
                    $deduction = 0;
                    $vendorIncentivesList = Incentives::where('user_id','=',$vendorId)->where('lead_id','=',$leadId)->get();

                    if(!empty($vendorIncentivesList)){
                        foreach ($vendorIncentivesList as $vendorIncentive) {
                            if($vendorIncentive->amount > 0){
                                $incentive += $vendorIncentive->amount;
                            } else if($vendorIncentive->amount < 0){
                                $deduction -= $vendorIncentive->amount;
                            }
                        }
                    }
                    $return[$mainCount-1]['leads'][$leadCounter]['incentive'] = $incentive;
                    $return[$mainCount-1]['leads'][$leadCounter]['deduction'] = $deduction;

                    $presentDays = 1;
                    $return[$mainCount-1]['leads'][$leadCounter]['presentDays'] = $presentDays;
                    $presentDays++;

                    $leadCounter++;                    
                } else {
                    $return[$mainCount-1]['leads'][$leadCounter-1]['presentDays'] = $presentDays;
                    $presentDays++;
                }


            }
        }
        
        return $return;
    }

}