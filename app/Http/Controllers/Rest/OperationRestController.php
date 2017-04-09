<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\INotificationRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Agency;
use App\Models\ORM\Gender;
use App\Models\ORM\LocationZone;
use App\Models\ORM\Qualification;
use App\Models\ORM\VendorSource;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Services\Domain\CaregiverDomainService;
use Cache;
class OperationRestController extends Controller
{
    protected $operationRestService;

    protected $commonRestServices;

    protected $notificationRestService;

    public function __construct( IOperationRestContract $operationRestService, ICommonRestContract $commonRestContract, INotificationRestContract $INotificationRestContract)
    {
        $this->operationRestService = $operationRestService;
        $this->commonRestServices = $commonRestContract;
        $this->notificationRestService = $INotificationRestContract;
    }

    public function submitEnquiry(){
        return Response::json($this->operationRestService->submitEnquiry(Input::all()));
    }

    public function submitEnquiryMobile(){
        return Response::json($this->operationRestService->submitEnquiryMobile(Input::all()));
    }
    public function submitEnquiryForCall(){
        return Response::json($this->operationRestService->submitEnquiryForCall(Input::all()));
    }
    public function verifyOTPForEnquiry(){
        return Response::json($this->operationRestService->verifyOTPForEnquiry(Input::all()));
    }
    public function submitEnquiryNotification($leadId){
        return Response::json($this->operationRestService->submitEnquiryNotification($leadId,Input::all()));
    }
    public function submitContact(){
        return Response::json($this->operationRestService->submitContact(Input::all()));
    }

    public function submitCRMLead(CaregiverDomainService $caregiverDomainService){
        //d(($this->operationRestService->submitOnlineLead(Input::all())));
        //return Response::json($this->operationRestService->submitCRMLead(Input::all(),Auth::user()->id));
        $lead = $this->operationRestService->submitCRMLead(Input::all(),Auth::user()->id);
        $preferred_cgs = $caregiverDomainService->populateCaregivers($lead->data->id);
        return Response::json($lead);   
    }
    public function updateLeadDetail($leadId,CaregiverDomainService $caregiverDomainService){
        //d(($this->operationRestService->submitOnlineLead(Input::all())));
        //return Response::json($this->operationRestService->updateLeadDetail($leadId,Input::all(),Auth::user()->id));    
        $lead = $this->operationRestService->updateLeadDetail($leadId,Input::all(),Auth::user()->id); 
        $preferred_cgs = $caregiverDomainService->populateCaregivers($lead->data->id);
        return Response::json($lead); 
    }

    public function getEnquiryGridData(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getEnquiryListGridData()));
    }
    public function getLeadGridData(){
        if(Input::get('count') && Input::get('count') > 0){
            return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getLeadListGridData(Input::all())));
        }else{
            $leads = Cache::remember('dashboard_all_lead_grid_data', 120, function () {
                   return $this->operationRestService->getLeadListGridData(Input::all());
               }); 
            return Response::json(PRResponse::getSuccessResponse("",$leads));
        }    
    }

    public function getClosedLeadGridData(){
        $leads = Cache::remember('dashboard_closed_lead_grid_data', 30, function () {
                   return $this->operationRestService->getClosedLeadListGridData();
               }); 
        return Response::json(PRResponse::getSuccessResponse("",$leads));
    }
    public function getPendingLeadGridData(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getPendingLeadListGridData()));
    }
    public function getValidatedLeadGridData(){
         $leads = Cache::remember('dashboard_validated_lead_grid_data', 30, function () {
                   return $this->operationRestService->getValidatedLeadListGridData();
               }); 
        return Response::json(PRResponse::getSuccessResponse("",$leads));
    }
    public function getStartedLeadGridData(){
        $leads = Cache::remember('dashboard_started_lead_grid_data', 30, function () {
                   return $this->operationRestService->getStartedLeadListGridData();
               }); 
        return Response::json(PRResponse::getSuccessResponse("",$leads));
    }
    public function getTodayLeadGridData(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getTodayLeadListGridData()));
    }

    public function getMappedOptions(){
        return Response::json(PRResponse::getSuccessResponse("",array(
            'services'=>$this->commonRestServices->getServicesList(true),
            'shifts'=>$this->commonRestServices->getShifts(),
            'references'=>$this->commonRestServices->getLeadReferences(),
            'ailments'=>$this->commonRestServices->getAilments(),
            'equipments'=>$this->commonRestServices->getEquipments(),
            'religions'=>$this->commonRestServices->getReligions(),
            'languages'=>$this->commonRestServices->getLanguages(),
            'physioConditions'=>$this->commonRestServices->getConditions(),
            'physioComplaints'=>$this->commonRestServices->getComplaints(),
            'presentconditions'=>$this->commonRestServices->getPtconditions(),
            'modalities'=>$this->commonRestServices->getModalities(),
            'ageRanges'=>$this->commonRestServices->getAgeRangesForFront(),
            'foodOptions'=>$this->commonRestServices->getFoodTypes(),
            'paymentType'=>$this->operationRestService->getPaymentType(),
            'paymentPeriod'=>$this->operationRestService->getPaymentPeriod(),
            'paymentMode'=>$this->operationRestService->getPaymentMode(),
            'priceUnit'=>$this->operationRestService->getPriceUnit(),
            'tasks'=>$this->operationRestService->getTasksList(),
            'mobilities'=>$this->operationRestService->getMobilities(),
            'genders'=>Gender::get(),
            'vendorSources'=>VendorSource::get(),
            'agencies'=>Agency::get(),
            'qualifications'=>Qualification::get(),
            'zones'=> LocationZone::get()
        )));
    }

    public function getTasksByService($serviceId){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getTasksByService($serviceId)));
    }

    public function getLeadDetail($leadId){
        $leadDetail = $this->operationRestService->getLeadDetail($leadId);
        if(!$this->operationRestService->isAuthorizedToViewContact()){
            $leadDetail->phone = 'xxxxxxxxxx';
        }
        return Response::json(PRResponse::getSuccessResponse("",$leadDetail));
    }

    public function getLeadComments($leadId){
        $leadCommentList = $this->operationRestService->getLeadComments($leadId);
        return Response::json(PRResponse::getSuccessResponse("",$leadCommentList));
    }
    public function submitLeadComment($leadId){
        $leadCommentSubmitResponse = $this->operationRestService->submitLeadComment($leadId,Input::get('comment'),Auth::user()->id);
        return Response::json($leadCommentSubmitResponse);
    }

    public function getLeadLogs($leadId){
        $leadLogsList = $this->operationRestService->getLeadLogs($leadId);
        return Response::json(PRResponse::getSuccessResponse("",$leadLogsList));
    }

    public function getAssignableEmployees(){
        $employeeListDto = $this->operationRestService->getAssignableEmployees();

        return Response::json(PRResponse::getSuccessResponse("",$employeeListDto));
    }

    public function assignEmployeeToLead($leadId){
        $employeeAssignmentResponse = $this->operationRestService->assignEmployeeToLead($leadId,Input::get('userId'),Auth::user()->id);
        return Response::json($employeeAssignmentResponse);
    }
    public function assignQcEmployeeToLead($leadId){
        $employeeAssignmentResponse = $this->operationRestService->assignQcEmployeeToLead($leadId,Input::get('userId'),Auth::user()->id);
        return Response::json($employeeAssignmentResponse);
    }
    public function assignFieldEmployeeToLead($leadId){
        $employeeAssignmentResponse = $this->operationRestService->assignFieldEmployeeToLead($leadId,Input::get('userId'),Auth::user()->id);
        return Response::json($employeeAssignmentResponse);
    }
    public function assignVendorToLead($leadId){
        $sourcingData = [];
        if(Input::has('sourcingData')){
            $sourcingData=Input::get('sourcingData');
        }
        $isPrimary = true;
        if(Input::has('primary')){
            $isPrimary = Input::get('primary');
        }
        $pricePerDay = null;
        if(Input::has('price')){
            $pricePerDay = floatval(Input::get('price'));
        }
        $employeeAssignmentResponse = $this->operationRestService->assignVendorToLead($leadId,Input::get('vendorId'),$pricePerDay, $isPrimary,$sourcingData,Auth::user()->id);
        return Response::json($employeeAssignmentResponse);
    }
    public function submitQCBriefing($leadId){
        $briefingData = [];
        if(Input::has('briefingData')){
            $briefingData=Input::get('briefingData');
        }
        $employeeAssignmentResponse = $this->operationRestService->submitQcBriefing($leadId,Input::get('qcAssignmentId'),$briefingData,Auth::user()->id);
        return Response::json($employeeAssignmentResponse);
    }

    public function getOperationalStatusesGrouped(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getOperationalStatusesGrouped()));
    }

    public function updateLeadStatus($leadId){
        return Response::json($this->operationRestService->updateLeadStatus($leadId,Input::all(),Auth::user()->id));
    }
    public function approveLead($leadId){
        return Response::json($this->operationRestService->approveLead($leadId,Input::all(),Auth::user()->id));
    }


    public function ailmentTasksMapped($serviceId){
        $data = $this->operationRestService->ailmentTasksMapped($serviceId);
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getAilmentList($serviceId) {
        $data = $this->operationRestService->getAilmentList($serviceId);
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }


    public function updateLeadPatientInfo($leadId){
        $inputAll = Input::all();
      // print_r($inputAll);
        return Response::json($this->operationRestService->updateLeadPatientInfoMobile($leadId,$inputAll));
    }
    public function updateLeadPhysioPatientInfo($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadPhysioPatientInfo($leadId,$inputAll));
    }
    public function updateLeadTaskInfo($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadTaskInfo($leadId,$inputAll));
    }
    public function updateLeadSpecialRequest($leadId){
        $inputAll = Input::all();
        return Response::json($this->operationRestService->updateLeadSpecialRequest($leadId,$inputAll));
    }
    public function getValidationTaskCategories(){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getValidationTaskCategory()));
    }
    public function getPatientValidationData($patientId){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getPatientValidationData($patientId)));
    }
    public function getPatientValidatedData($patientId){
        return Response::json(PRResponse::getSuccessResponse("",$this->operationRestService->getPatientValidatedData($patientId)));
    }

    public function submitMail(){
        return view('emails.welcome');

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
        $mail->addAddress('mohit2007gupta@gmail.com', 'Pramati User');     // Add a recipient
        $mail->addReplyTo(env('PHPMAILER_REPLY_EMAIL'), env('PHPMAILER_REPLY_NAME'));
   // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = view('emails.welcome');
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    public function callMeNow($leadId){
        $this->operationRestService->callMeNow($leadId);
    }

    public function uploadPrescription(){
        $prescriptionDto = $this->operationRestService->uploadPrescription(Input::file('file'), null);
        if($prescriptionDto){
            return Response::json(PRResponse::getSuccessResponse("",$prescriptionDto));
        }
        return Response::json(PRResponse::getErrorResponse("",false));
    }

    public function demo(){
        return view('admin.demo');
    }


    public function sendMailToCustomer($leadId) {
        return $this->operationRestService->sendMailToCustomer($leadId);
    }

    public function deleteLead($leadId){
        $authenticateUser = Auth::user();
        if($authenticateUser->is_admin!=true){
            return Response::json(PRResponse::getWarningResponse('You are not authorized to delete lead.',array()));
        }
        return Response::json(PRResponse::getSuccessResponse("Successfully deleted the lead",$this->operationRestService->deleteLead($leadId)));
    }

    public function viewLeadContactInformation($leadId){
        return Response::json($this->operationRestService->viewLeadContact($leadId));
    }

    public function startLeadService($leadId){
        return Response::json($this->operationRestService->startLeadService($leadId,Input::all()));
    }

    public function deleteBulkLead(){
        if((Auth::user()->is_admin)!=1){
            return Response::json(PRResponse::getWarningResponse("You are not authorized to delete leads",array()));
        }
        return Response::json($this->operationRestService->deleteBulkLead(Input::all()));
    }

    public function getLeadCarePlanGrid($leadId){
        $leadPlanGrid = $this->operationRestService->getCarePlanData($leadId);
        $toReturn = array();

        foreach($leadPlanGrid as $tempCat){
            foreach($tempCat->tasks as $tempTask){
                if($tempTask->validation==true){
                    array_push($toReturn,$tempTask);
                }
            }
        }
        return Response::json(PRResponse::getSuccessResponse("",$toReturn));
    }

    public function submitCarePlanEvaluationData($action,$leadId){
        return Response::json($this->operationRestService->submitCarePlanEvaluationData($action,$leadId,Input::all(),Auth::user()->id));
    }

    public function submitCGAttendance($leadId){
        return Response::json($this->operationRestService->submitCGAttendance($leadId,Input::all(),Auth::user()->id));
    }

    public function submitCGAttendanceBySMS(){
        return Response::json($this->operationRestService->submitCGAttendanceBySMS(Input::all()));
    }

    public function syncSlackComment($leadId){

        $this->operationRestService->syncLeadSlackComment($leadId);
    }

    public function getCustomerNotificationTemplates(){
        return Response::json($this->notificationRestService->getCustomerNotificationTemplates());
    }

    public function submitCustomerNotification($leadId){
        return Response::json($this->notificationRestService->submitCustomerNotification($leadId,Input::all()));
    }

    public function connectLeadTableToUser(){
        return Response::json($this->operationRestService->connectLeadTableToUser());
    }

    public function getAllotableEmployeesComplaints(){
        $employeeListDto = $this->operationRestService->getAllotableEmployeesComplaints();

        return Response::json(PRResponse::getSuccessResponse("",$employeeListDto));
    }

    public function getCurrentVendor($leadId){
        return Response::json($this->operationRestService->getCurrentVendor($leadId));
    }

    public function cronTester($vendorId){
        return Response::json($this->operationRestService->cronTester($vendorId));
    }

}