<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IOperationDomainContract;
use App\Services\Domain\BillingDomainService;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IFireBasePushHelperContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Contracts\Rest\INotificationRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Models\DTO\ActiveLeadAttendanceDTO;
use App\Models\DTO\AilmentDTO;
use App\Models\DTO\Careplan\PatientCarePlanTaskCategoryDTO;
use App\Models\DTO\Careplan\PatientCarePlanTaskDTO;
use App\Models\DTO\ClosedLeadGridItemDTO;
use App\Models\DTO\CustomerNotificationDTO;
use App\Models\DTO\CustomerStatusRequestDTO;
use App\Models\DTO\CustomerVendorAttendanceDTO;
use App\Models\DTO\VendorIncentiveDTO;
use App\Models\DTO\CustomerVendorStatusDTO;
use App\Models\DTO\EnquiryGridItemDTO;
use App\Models\DTO\GridDataDTO;
use App\Models\DTO\LeadCommentDTO;
use App\Models\DTO\ComplaintLogsDTO;
use App\Models\DTO\LeadDetailedDTO;
use App\Models\DTO\LeadEmployeeDTO;
use App\Models\DTO\LeadGridItemDTO;
use App\Models\DTO\LeadLogDTO;
use App\Models\DTO\LeadQCEmployeeDTO;
use App\Models\DTO\LeadReferenceDTO;
use App\Models\DTO\LeadStatusDTO;
use App\Models\DTO\LeadVendorAttendanceDTO;
use App\Models\DTO\LeadVendorDTO;
use App\Models\DTO\Mobile\CustomerLeadItemDTO;
use App\Models\DTO\Mobile\CustomerServiceListItemDTO;
use App\Models\DTO\MobilityDTO;
use App\Models\DTO\OperationalStatusDTO;
use App\Models\DTO\OperationalStatusGroupDTO;
use App\Models\DTO\OperationalStatusMinimalDTO;
use App\Models\DTO\PatientDTO;
use App\Models\DTO\PatientPrescriptionDTO;
use App\Models\DTO\PatientValidationDTO;
use App\Models\DTO\PaymentModeDTO;
use App\Models\DTO\PaymentPeriodDTO;
use App\Models\DTO\PaymentTypeDTO;
use App\Models\DTO\PriceUnitDTO;
use App\Models\DTO\Reports\ActiveProjectGridItemDTO;
use App\Models\DTO\TaskCategoryDTO;
use App\Models\DTO\TaskDTO;
use App\Models\DTO\UserInfoDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\Enums\PramatiConstants;
use App\Models\Enums\SCConstants;
use App\Models\ORM\CustomerNotification;
use App\Models\ORM\Lead;
use App\Models\ORM\LeadComment;
use App\Models\ORM\LeadEmployee;
use App\Models\ORM\LeadReference;
use App\Models\ORM\Locality;
use App\Models\ORM\Mobility;
use App\Models\ORM\Patient;
use App\Models\ORM\PatientPhysiotherapy;
use App\Models\ORM\PaymentMode;
use App\Models\ORM\PaymentPeriod;
use App\Models\ORM\PaymentType;
use App\Models\ORM\PriceUnit;
use App\Templates\PRResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Maknz\Slack\Client;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;
use App\Models\ORM\Incentives;
use App\Services\Domain\VendorDomainService;
use App\Models\ORM\LeadActiveDate;

class OperationRestService implements IOperationRestContract
{
    protected $operationDomainService;
    
    protected $billingDomainService;

    protected $mailHelperService;

    protected $userDomainService;

    protected $slackHelperService;

    protected $fireBasePushHelperService;

    protected $notificationRestService;

    public function __construct(IOperationDomainContract $operationDomainService, IMailHelperContract $mailHelperContract, IUserDomainContract $IUserDomainContract, ISlackHelperContract $ISlackHelperContract, IFireBasePushHelperContract $IFireBasePushHelperContract,INotificationRestContract $INotificationRestContract, BillingDomainService $billingDomainService)
    {
        $this->operationDomainService = $operationDomainService;

        $this->mailHelperService = $mailHelperContract;

        $this->userDomainService = $IUserDomainContract;

        $this->slackHelperService = $ISlackHelperContract;

        $this->fireBasePushHelperService = $IFireBasePushHelperContract;

        $this->notificationRestService = $INotificationRestContract;
        
        $this->billingDomainService = $billingDomainService;
    }

    public function submitEnquiry($inputAll){
        $leadData = $inputAll;
        $serviceId = $inputAll['serviceId'];
        $customerInfo = $inputAll['customerInfo'];
        $name = $customerInfo['name'];
        $email = $customerInfo['email'];
        $phone = $customerInfo['phone'];

        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);
        $leadSubmitResponse = $this->operationDomainService->submitLeadEnquiry($name,$email,$phone,$localityOrm,$serviceId);
        if($leadSubmitResponse){
            $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadSubmitResponse->id);
            $this->mailHelperService->sendWelcomeMailToCustomer($leadDetailedOrm);


            // push notification



            $this->mailHelperService->sendNewLeadCreationEmail($leadDetailedOrm);
            $this->mailHelperService->sendMailForEmployeeAssignment($leadDetailedOrm);

            // send sms
            /*$smsToList = ['9015044485','9873254690','7042418821'];
        
            $smsContent = 'New lead generated: '.$leadDetailedOrm->customer_name." ".$leadDetailedOrm->customer_last_name;

            $smsUrl = 'http://www.interconnectsoft.com/sendurlcomma.aspx?';
            $url = 'user='.env('SMS_USER');
            $url.= '&pwd='.env('SMS_PASSWORD');
            $url.= '&senderid='.env('SMS_SENDER_ID');
            $url.= '&mobileno='.implode(',', $smsToList);
            $url.= '&msgtext='.urlencode($smsContent);
            $url.= '&smstype=13';
            $url.= '&dnd=1';
            $url.= '&unicode=0';

            $urlToUse =  $smsUrl.$url;

            $ch = curl_init($urlToUse);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            //echo $curl_scraped_page;
            curl_close($ch);*/

            return PRResponse::getSuccessResponse("Successfully added enquiry. Will call you.",$leadSubmitResponse->id);
        }
        return;
    }

    public function submitEnquiryMobile($inputAll){
        $leadData = $inputAll;
        $serviceId = $inputAll['serviceId'];
        $name = $inputAll['name'];
        $email = $inputAll['email'];

        $phone = $inputAll['phone'];

        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);
        $leadSubmitResponse = $this->operationDomainService->submitLeadEnquiry($name,$email,$phone,$localityOrm,$serviceId);
        if($leadSubmitResponse){
            $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadSubmitResponse->id);
            //$this->mailHelperService->sendWelcomeMailToCustomer($leadDetailedOrm);


            // push notification



            //$this->mailHelperService->sendNewLeadCreationEmail($leadDetailedOrm);
            //$this->mailHelperService->sendMailForEmployeeAssignment($leadDetailedOrm);
            return PRResponse::getSuccessResponse("Successfully added enquiry. Will call you.",$leadSubmitResponse->id);
        }
        return;
    }

    public function submitEnquiryForCall($inputAll){
        $leadData = $inputAll;
        $serviceId = $inputAll['serviceId'];
        $customerInfo = $inputAll['customerInfo'];
        $name = $customerInfo['name'];
        $email = $customerInfo['email'];
        $phone = $customerInfo['phone'];

        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);
        $leadSubmitResponse = $this->operationDomainService->submitLeadEnquiry($name,$email,$phone,$localityOrm,$serviceId);
        if($leadSubmitResponse){
            // generate OTP and send
            // echo "s";
            $this->operationDomainService->generateOtpForPhone($leadSubmitResponse->id,$phone);
            return PRResponse::getSuccessResponse("Successfully added enquiry. Will call you.",$leadSubmitResponse->id);
        }
        return;
    }

    public function verifyOTPForEnquiry($inputAll){
        $leadId = $inputAll['leadId'];
        $otp = $inputAll['otp'];

        $otpVerified = $this->operationDomainService->verifyLeadOtp($leadId,$otp);

        if($otpVerified){
            return PRResponse::getSuccessResponse("Successfully verified otp. Generating call.",$leadId);
        }else{
            return PRResponse::getErrorResponse("Unable to verify otp.Please try again.",$leadId);
        }
        return;
    }

    public function callMeNow($leadId){
        $this->operationDomainService->callLead($leadId,env('MYOPERATOR_SUPPORT_USERID'));
    }

    public function submitEnquiryNotification($leadId,$inputAll){
        $leadData = $inputAll;
        $serviceId = $inputAll['serviceId'];
        $customerInfo = $inputAll['customerInfo'];
        $name = $customerInfo['name'];
        $email = $customerInfo['email'];
        $phone = $customerInfo['phone'];

        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);

        $leadSubmitResponse = $this->operationDomainService->submitLeadEnquiryNotification($leadId,$name,$email,$phone,$localityOrm,$serviceId);
        // Send notification mail to admin and help desk team
        if($leadSubmitResponse){
            return PRResponse::getSuccessResponse("Successfully added enquiry. Will call you.",$leadSubmitResponse->id);
        }
        return;
    }
    public function submitContact($inputAll){
        $name = $inputAll['name'];
        $email = $inputAll['email'];
        $phone = $inputAll['phone'];
        $message = $inputAll['message'];
        $leadSubmitResponse = $this->operationDomainService->submitEnquiry($name,$email,$phone,$message);
        if($leadSubmitResponse){
            return PRResponse::getSuccessResponse("Successfully added enquiry. Will call you.",$leadSubmitResponse->id);
        }
        return;
    }

    private function preparePatientPhysioOrm($physioInput){
        $physioOrmNew = new PatientPhysiotherapy();
        if(isset($physioInput['condition']) && isset($physioInput['condition']['id']) && $physioInput['condition']['id']!=""){
            $physioOrmNew->condition_id = $physioInput['condition']['id'];
        }else{
            $physioOrmNew->condition_id=null;
        }
        $physioOrmNew->pain_severity = $physioInput['painSeverity'];
        $physioOrmNew->motion_range = $physioInput['rangeOfMotion'];
        $physioOrmNew->present_condition = isset($physioInput['presentCondition'])?$physioInput['presentCondition']:'';
        $physioOrmNew->no_of_sessions = $physioInput['noOfSessions'];
        if(isset($physioInput['modality']) && isset($physioInput['modality']['id']) && $physioInput['modality']['id']!=""){
            $physioOrmNew->modality_id = $physioInput['modality']['id'];
        }else{
            $physioOrmNew->modality_id = null;
        }
        return $physioOrmNew;
    }
    private function prepareLeadOrm($inputAll,$enquiryOrm){
        $leadOrm = new Lead();
        $leadOrm->enquiry_id = null;
        $leadOrm->service_id = $inputAll['service']['id'];
        $leadOrm->lead_status_id = '0';
        $leadOrm->service_sub_category_id = 0;
        $leadOrm->customer_name = $enquiryOrm['name'];
        $leadOrm->task_other = $inputAll['taskOther'];

        $leadOrm->customer_last_name = $enquiryOrm['lastName'];
        $leadOrm->locality_id = 0;
        if(isset($enquiryOrm['phone'])){
            $leadOrm->phone = $enquiryOrm['phone'];
        }
        if(isset($inputAll['address'])){
            $leadOrm->address = $inputAll['address'];
        }else{
            $leadOrm->address = '';
        }
        if(isset($inputAll['landmark'])){
            $leadOrm->landmark = $inputAll['landmark'];
        }else{
            $leadOrm->landmark = '';
        }

        $leadOrm->city_id = null;
        if(isset($enquiryOrm['email'])){
            $leadOrm->email = $enquiryOrm['email'];
        }else{
            $leadOrm->email = '';
        }
        if(isset($enquiryOrm['email']))
        {
            $leadOrm->email = $enquiryOrm['email'];
        }
      //  $leadOrm->email = $enquiryOrm['email'];


        if(isset($inputAll['reference']['id'])){
            $leadOrm->reference_id = $inputAll['reference']['id'];
        }
        if(isset($inputAll['reference']) && isset($inputAll['reference']['child']) && isset($inputAll['reference']['child']['id'])){
            $leadOrm->reference_id = $inputAll['reference']['child']['id'];
        }
        if(isset($inputAll['referenceData']) && isset($inputAll['referenceData']['id'])){
            $leadOrm->reference_data = $inputAll['referenceData']['id'];
        }else if(isset($inputAll['referenceData'])){
            $leadOrm->reference_data = $inputAll['referenceData'];
        }
        $leadOrm->user_id = null;
        $leadOrm->source_id = null;
        if(isset($inputAll['leadSource']) && isset($inputAll['leadSource']['id'])){
            $leadOrm->source_id=$inputAll['leadSource']['id'];
        }
        if(isset($inputAll['request']['startDate'])){
            $startDateTimeStamp = strtotime($inputAll['request']['startDate']);
            $startDateCarbon = Carbon::now();
            $startDateCarbon->timestamp($startDateTimeStamp);
            $startDateCarbon->second = 0;
            $leadOrm->start_date = $startDateCarbon;
        }
        if(isset($inputAll['request']['endDate'])){
             $leadOrm->end_date = $inputAll['request']['endDate'];
         }
        $leadOrm->number_of_days = $inputAll['request']['duration'];
         if(isset($inputAll['payment']['mode']) && isset($inputAll['payment']['mode']['id'])){
             $leadOrm->payment_mode_id = $inputAll['payment']['mode']['id'];
         }else{
             $leadOrm->payment_mode_id = null;
         }
        if(isset($inputAll['payment']['type']) && isset($inputAll['payment']['type']['id'])){
            $leadOrm->payment_type_id = $inputAll['payment']['type']['id'];
        }else{
            $leadOrm->payment_type_id = null;
        }
        if(isset($inputAll['payment']['period']) && isset($inputAll['payment']['period']['id'])){
            $leadOrm->payment_period_id = $inputAll['payment']['period']['id'];
        }else{
            $leadOrm->payment_period_id = null;
        }
        $leadOrm->remark =$inputAll['request']['remark'];
        return $leadOrm;
    }
    public function preparePatientOrm($inputAll){
        $patientOrm = new Patient();
        // dd($inputAll);
        $patientOrm->name = $inputAll['patientInfo']['name'];
        if(isset( $inputAll['patientInfo']['gender']['id'])){
            $patientOrm->gender = $inputAll['patientInfo']['gender']['id'];
        }else{
            $patientOrm->gender = '';
        }
        $patientOrm->age = $inputAll['patientInfo']['age'];
        $patientOrm->weight = $inputAll['patientInfo']['weight'];
        $patientOrm->is_on_equipment = $inputAll['patientInfo']['equipmentSupport'];
        if(isset($inputAll['patientInfo']['equipments']['id'])){
            $patientOrm->equipment_id = $inputAll['patientInfo']['equipments']['id'];
        }else{
            $patientOrm->equipment_id = '';
        }
        if(isset($inputAll['shift']['id'])){
            $patientOrm->shift_id = $inputAll['shift']['id'];
        }else{
            $patientOrm->shift_id = '';
        }
        $patientOrm->other_ailment = $inputAll['patientInfo']['otherAilment'];


        if(isset($inputAll['patientInfo']['mobility']) && isset($inputAll['patientInfo']['mobility']['id'])){
            $patientOrm->mobility_id = $inputAll['patientInfo']['mobility']['id'];
        }else{
            $patientOrm->mobility_id = null;
        }

        $patientOrm->illness = $inputAll['patientInfo']['illness'];;
        $patientOrm->physical_condition = $inputAll['patientInfo']['physicalCondition'];
        $patientOrm->morning_wakeup_time = $inputAll['patientInfo']['morningWakeupTime'];
        $patientOrm->breakfast_time = $inputAll['patientInfo']['morningBreakfastTime'];;
        $patientOrm->lunch_time = $inputAll['patientInfo']['lunchTime'];
        $patientOrm->dinner_time = $inputAll['patientInfo']['dinnerTime'];
        $patientOrm->walk_time = $inputAll['patientInfo']['walkingTime'];
        $patientOrm->walk_location = $inputAll['patientInfo']['walkingLocation'];;
        $patientOrm->religion_preference = $inputAll['request']['religion'];
        if(isset($inputAll['request']['religionRequired']['id'])){
            $patientOrm->religion_preferred = $inputAll['request']['religionRequired']['id'];
        }else{
            $patientOrm->religion_preferred = 0;
        }
        $patientOrm->gender_preference = $inputAll['request']['gender'];
        if(isset( $inputAll['request']['genderRequired']['id'])){
            $patientOrm->gender_preferred = $inputAll['request']['genderRequired']['id'];
        }else{
            $patientOrm->gender_preferred = 0;
        }
        $patientOrm->language_preference = $inputAll['request']['language'];
        if(isset($inputAll['request']['languageRequired']['id'])){
            $patientOrm->language_preferred = $inputAll['request']['languageRequired']['id'];
        }else{
            $patientOrm->language_preferred = 0;
        }

        $patientOrm->age_preference = $inputAll['request']['age'];
        if(isset($inputAll['request']['ageRequired']['id'])){
            $patientOrm->age_preferred = $inputAll['request']['ageRequired']['id'];
        }else{
            $patientOrm->age_preferred = 0;
        }

        $patientOrm->food_preference = $inputAll['request']['food'];
        if(isset($inputAll['request']['foodRequired']['id'])){
            $patientOrm->food_preferred = $inputAll['request']['foodRequired']['id'];
        }else{
            $patientOrm->food_preferred = 0;
        }
        if(isset($inputAll['patientInfo']['doctorName'])){
            $patientOrm->doctor_name = $inputAll['patientInfo']['doctorName'];
        }else{
            $patientOrm->doctor_name='';
        }
        //d($inputAll);
        if(isset($inputAll['patientInfo']['hospitalName'])){
            $patientOrm->hospital_name = $inputAll['patientInfo']['hospitalName'];
        }else{
            $patientOrm->hospital_name='';
        }



        return $patientOrm;
    }

    public function prepareLocalityOrm($inputLocality){

        if(!isset($inputLocality['id'])){
            return false;
        }
        $localityOrm = new Locality();
        $localityOrm->json = json_encode($inputLocality);
        $localityOrm->formatted_address = $inputLocality['formatted_address'];
        return $localityOrm;
    }



    public function updateLeadDetail($leadId,$inputAll, $userId){
        $leadObject = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $leadOrm = $this->prepareLeadOrm($inputAll, $inputAll['enquiry']);
        if(!$this->isAuthorizedToViewContact()){
            $leadOrm->phone = $leadObject->phone;
        }
        // locality check to update or not
        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);

        if($localityOrm && $leadObject && $leadObject->locality && $leadObject->locality->formatted_address != $localityOrm->formatted_address){
            $localityOrm = $this->operationDomainService->createLocalityByOrm($localityOrm);
            $leadOrm->locality_id = $localityOrm->id;
        }else if(!$leadObject->locality){
            $localityOrm = $this->operationDomainService->createLocalityByOrm($localityOrm);
            $leadOrm->locality_id = $localityOrm->id;
        }else{
            $leadOrm->locality_id = $leadObject->locality->id;
        }
        $patientOrm = $this->preparePatientOrm($inputAll);
        $inputAll['patientInfo']['shift'] = $inputAll['shift'];
        // sortOut Ailments
        $ailmentList = $inputAll['patientInfo']['ailments'];
        $ailmentlistArray = [];
        foreach($ailmentList as $tempAilment){
            if(isset($tempAilment['id'])){
                array_push($ailmentlistArray,$tempAilment['id']);
            }else{
                array_push($ailmentlistArray,$tempAilment);
            }
        }

        if(isset($inputAll['payment']['price'])){
            $price = $inputAll['payment']['price'];
        }else{
            $price='';
        }
        if(isset($inputAll['payment']['priceUnit']) && isset($inputAll['payment']['priceUnit']['id'])){
            $priceUnit = $inputAll['payment']['priceUnit']['id'];
        }else{
            $priceUnit = 0;
        }
        $leadPriceData = $this->operationDomainService->updatePriceToLead($leadId,$price,$priceUnit);

        $leadPatientUpdateResponse = $this->operationDomainService->updatePatientInfo($leadId,$inputAll['patientInfo']);


//        d($ailmentlistArray);
  //      die();
    //    $this->operationDomainService->updatePatientAilments($ailmentlistArray);

        $leadSpecialRequestUpdateResponse = $this->operationDomainService->updateLeadSpecialRequestCRM($leadId,$inputAll['request']);

        $leadResponseOrm = $this->operationDomainService->updateLeadByOrm($leadId,$leadOrm);



        $toUpdateTask = array();
        foreach($inputAll['tasks'] as $tempTask){
            if(isset($tempTask['id'])){
                array_push($toUpdateTask,$tempTask['id']);
            }else{
                array_push($toUpdateTask,$tempTask);
            }
        }

        $updatePatientTaskResponse = $this->operationDomainService->updateTaskInfo($leadId,array(
            'tasks'=>$toUpdateTask,
            'shift'=> (isset($inputAll['shift']['id'])?$inputAll['shift']['id']:null)
        ));

        // update validation data
        if(isset($inputAll['validationInfo'])){
            $validationTaskList = array();
            if(isset($inputAll['validationInfo']['tasks'])){
                foreach($inputAll['validationInfo']['tasks'] as $tempTaskCat){
                    foreach($tempTaskCat['tasks'] as $taskItem){
                        if(isset($taskItem['selected']) && $taskItem['selected']==true){
                            array_push($validationTaskList,$taskItem['id']);
                        }
                    }
                }
            }
            $leadValidationResponse = $this->operationDomainService->updatePatientValidationInfo($leadPatientUpdateResponse->id,$inputAll['validationInfo'],$validationTaskList);
            if($leadValidationResponse){


                $leadValidatedStatus = $this->operationDomainService->getStatusBySlug('validated');

                //$this->operationDomainService->updateLeadStatus($leadId,$leadValidatedStatus->id,$userId);
            }
        }


        if($leadResponseOrm && $leadPatientUpdateResponse && $leadSpecialRequestUpdateResponse && $updatePatientTaskResponse){
            $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);
            $leadDetailedDto = new LeadDetailedDTO();
            return PRResponse::getSuccessResponse("Lead updated successfully",$leadDetailedDto->convertToDto($leadDetailedOrm));
        }
        return PRResponse::getErrorResponse("Unable to create lead, please contact system administrator",array());
    }

    public function submitCRMLead($inputAll,$userId){

        $leadOrm = $this->prepareLeadOrm($inputAll, $inputAll['enquiry']);
        $leadOrm->user_id = $userId;
        $leadOrm->submission_mode = "crm";
        $leadOrm->submission_complete= true;
       //dd($inputAll);

        $localityOrm = $this->prepareLocalityOrm($inputAll['locality']);
        if($localityOrm){
            $localityOrm = $this->operationDomainService->createLocalityByOrm($localityOrm);
            $leadOrm->locality_id = $localityOrm->id;
        }
        $patientOrm = $this->preparePatientOrm($inputAll);
       // dd($patientOrm);

        $leadResponseOrm = $this->operationDomainService->createLeadByOrm($leadOrm);
        $patientOrm->lead_id = $leadResponseOrm->id;
        //d($patientOrm);
        $patientSaveResponse = $this->operationDomainService->createPatientByOrm($patientOrm);
        //adding patient equipment
        $patientEquipment = $this->operationDomainService->addPatientEquipmentDetail($patientSaveResponse->id,$inputAll['patientInfo']['equipments']);

        // adding patient ailment
        $updatePatientAilment = $this->operationDomainService->addpatientAilment($patientSaveResponse->id,$inputAll['patientInfo']['ailment']);
        $updatePatientPrescription = $this->operationDomainService->addpatientPrescription($patientSaveResponse->id,$inputAll['patientInfo']['prescriptionList']);


        /*$toUpdateTask = array();
        foreach($inputAll['tasks'] as $tempTask){
            if(isset($tempTask['id'])){
                array_push($toUpdateTask,$tempTask['id']);
            }else{
                array_push($toUpdateTask,$tempTask);
            }
        }

        $updatePatientTask = $this->operationDomainService->updateTaskInfo($leadOrm->id,array(
            'tasks'=>$toUpdateTask,
            'shift'=> (isset($inputAll['shift']['id'])?$inputAll['shift']['id']:null)
        ));*/

        // update validation data
        if(isset($inputAll['validationData'])){
            $validationTaskList = array();
            if(isset($inputAll['validationData']['tasks'])){
                foreach($inputAll['validationData']['tasks'] as $tempTaskCat){
                    foreach($tempTaskCat['tasks'] as $taskItem){
                        if(isset($taskItem['selected']) && $taskItem['selected']==true){
                            array_push($validationTaskList,$taskItem['id']);
                        }
                    }
                }
            }
            $leadValidationResponse = $this->operationDomainService->updatePatientValidationInfo($patientOrm->id,null,$validationTaskList);

        }
        if(isset($inputAll['taskOther'])){
            $taskOther = $inputAll['taskOther'];
        }
        if(isset($inputAll['payment']['price'])){
            $price = $inputAll['payment']['price'];
        }else{
            $price='';
        }
        if(isset($inputAll['payment']['priceunit']) && isset($inputAll['payment']['priceunit']['id'])){
            $priceUnit = $inputAll['payment']['priceunit']['id'];
        }else{
            $priceUnit = 0;
        }
        $leadPriceData = $this->operationDomainService->addPriceToLead($leadResponseOrm->id,$price,$priceUnit);


        // physioinfo
        if($leadOrm->service_id==3){
            $physioOrm = $this->preparePatientPhysioOrm($inputAll['physio']);
            $this->operationDomainService->updatePhysioInfoCRM($leadOrm->id,$physioOrm);
        }

        // approve lead
        if($leadResponseOrm){
            $leadApproveStatus = $this->operationDomainService->getStatusBySlug('lead-approved');
            $leadStatusToUpdate = $this->operationDomainService->updateLeadStatus($leadOrm->id,$leadApproveStatus->id,$userId, null, null, null, null);
        }



        // register user

        $phone = $inputAll['enquiry']['phone'];
        if(isset($inputAll['enquiry']['email'])){
            $email = $inputAll['enquiry']['email'];
        }else{
            $email = '';
        }

        $userByPhone = $this->userDomainService->getUserByPhone($phone);
        $customerByPhone = $this->userDomainService->getCustomerByPhone($phone);
        /*$userByEmail=null;
        if($email!=''){
            $userByEmail = $this->userDomainService->getUserByEmail($email);
            if($userByPhone && $userByEmail && $userByPhone->id != $userByEmail->id){
                return PRResponse::getErrorResponse("User with same email already exist",(object)array());
            }
        }*/


        if(!$userByPhone && !$customerByPhone){
            $createCustomerByData = array(
                'phone' => $phone,
                'name' => trim($inputAll['enquiry']['name'].' '.$inputAll['enquiry']['lastname']),
                'email' => $email
            );
            $userByPhone = $this->userDomainService->createCustomerByData($createCustomerByData);
        }else if(!$userByPhone && $customerByPhone){
            $userByPhone = $this->userDomainService->createCustomerByPhone($customerByPhone,$phone);
        }else if($userByPhone && $customerByPhone){
            $this->userDomainService->updateCustomerPhoneOnLead($userByPhone->id,$phone,false);
        }

        // ./register user



        if($leadResponseOrm && $patientSaveResponse && $updatePatientAilment && $localityOrm){

            // mail on lead creation

            $this->operationDomainService->sendNewLeadNotification($leadOrm->id);

            $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadOrm->id);
            // send mail to Mayur for employee assignment
            /*$this->mailHelperService->sendWelcomeMailToCustomer($leadDetailedOrm);
            $this->mailHelperService->sendNewLeadCreationEmail($leadDetailedOrm);
            $this->mailHelperService->sendMailForEmployeeAssignment($leadDetailedOrm);*/


            $leadDetailedOrm = $leadDetailedOrm;
            $leadDetailedDto = new LeadDetailedDTO();
            return PRResponse::getSuccessResponse("Lead created successfully",$leadDetailedDto->convertToDto($leadDetailedOrm));
        }
        return PRResponse::getErrorResponse("Unable to create lead, please contact system administrator",array());

    }

    public function getTasksByService($serviceId){
        $taskList = $this->operationDomainService->getTasksByService($serviceId);
        $taskDTOList = [];
        $taskDto = new TaskDTO();
        foreach($taskList as $tempTaskItem){
            array_push($taskDTOList,$taskDto->convertToDTO($tempTaskItem,$serviceId));
        }
        return $taskDTOList;
    }
    public function getTasksList(){
        $taskList = $this->operationDomainService->getAllTask();
        $taskDTOList = [];
        $taskDto = new TaskDTO();
        foreach($taskList as $tempTaskItem){
            array_push($taskDTOList,$taskDto->convertToDTO($tempTaskItem, null));
        }
        return $taskDTOList;
    }

    public function getEnquiryListGridData(){
        $enquiryGridItemList = [];
        $enquiryOrmList = $this->operationDomainService->getEnquiryList();
        $enquiryGridItemDto = new EnquiryGridItemDTO();
        foreach($enquiryOrmList as $tempEnquiry){
            array_push($enquiryGridItemList,$enquiryGridItemDto->convertToDto($tempEnquiry));
        }

        return $enquiryGridItemList;
    }
    public function getLeadListGridData($inputAll){
        $leadGridItemList = [];
        $leadOrmList =[];
        if(isset($inputAll['count'])){
            $leadOrmList = $this->operationDomainService->getLeadList(intval($inputAll['count']));
        }else{
            $leadOrmList = $this->operationDomainService->getLeadList();
        }

        $leadGridItemDto = new LeadGridItemDTO();
        foreach($leadOrmList as $tempLead){
            array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
        }
        return $leadGridItemList;
    }
    public function getClosedLeadListGridData(){
        $leadGridItemList = [];
        $leadOrmList = $this->operationDomainService->getClosedLeadList();
        $closeStatus = $this->operationDomainService->getStatusBySlug('closed');
        $holdStatus = $this->operationDomainService->getStatusBySlug('hold');
        $leadGridItemDto = new ClosedLeadGridItemDTO();
        foreach($leadOrmList as $tempLead){
            if($tempLead->lead && ($tempLead->lead->current_status == $closeStatus->id || $tempLead->lead->current_status == $holdStatus->id)){
                array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
            }          
        }
        return $leadGridItemList;
    }
    public function getPendingLeadListGridData(){
        $leadGridItemList = [];
        $leadStatus = $this->operationDomainService->getStatusBySlug('pending');
        $leadOrmList = $this->operationDomainService->getLeadByStatus(0);
        $leadGridItemDto = new LeadGridItemDTO();
        foreach($leadOrmList as $tempLead){

            array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
        }
        return $leadGridItemList;
    }
    public function getValidatedLeadListGridData(){
        $leadGridItemList = [];
        $leadStatus = $this->operationDomainService->getStatusBySlug('validated');
        $leadOrmList = $this->operationDomainService->getLeadByStatus($leadStatus->id);
        $leadGridItemDto = new LeadGridItemDTO();
        foreach($leadOrmList as $tempLead){

            array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
        }
        return $leadGridItemList;
    }
    public function getStartedLeadListGridData(){
        $leadGridItemList = [];
        $leadStatus = $this->operationDomainService->getStatusBySlug('started');
        $leadOrmList = $this->operationDomainService->getLeadByStatus($leadStatus->id);
        $leadGridItemDto = new LeadGridItemDTO();
        foreach($leadOrmList as $tempLead){

            array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
        }
        return $leadGridItemList;
    }
    public function getTodayLeadListGridData(){
        $leadGridItemList = [];
        $leadOrmList = $this->operationDomainService->getTodayLead();
        $leadGridItemDto = new LeadGridItemDTO();
        foreach($leadOrmList as $tempLead){

            array_push($leadGridItemList,$leadGridItemDto->convertToDto($tempLead));
        }
        return $leadGridItemList;
    }
    public function getLeadDetail($leadId){
        $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);

        //d($leadDetailedOrm->employeesAssigned);
        $leadDetailedDto = new LeadDetailedDTO();
        $leadDetailedDto = $leadDetailedDto->convertToDto($leadDetailedOrm);

        if(!$this->isAuthorizedToViewContact()){
            $leadDetailedDto->price = "xxxx";
        }
        return $leadDetailedDto;
    }
    public function getLeadComments($leadId){
        $leadCommentsOrmCollection = $this->operationDomainService->getLeadCommentsCollection($leadId);
        $toReturnArray = [];
        $leadCommentDto = new LeadCommentDTO();
        foreach($leadCommentsOrmCollection as $tempLeadComment){
            array_push($toReturnArray,$leadCommentDto->convertToDTO($tempLeadComment));
        }
        return $toReturnArray;
    }

    public function getComplaintLogs($leadId){
        $ComplaintLogsOrmCollection = $this->operationDomainService->getComplaintLogs($leadId);
        $toReturnArray = [];
        $complaintLogsDto = new ComplaintLogsDTO();
        foreach($ComplaintLogsOrmCollection as $tempComplaintLog){
            array_push($toReturnArray,$complaintLogsDto->convertToDTO($tempComplaintLog));
        }
        return $toReturnArray;
    }

    public function submitLeadComment($leadId,$comment,$userId){
        $commentResponse = $this->operationDomainService->submitLeadComment($leadId,$comment,$userId);
        $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
        $response = PRResponse::getErrorResponse('Unable to submit comment please try after sometime.',$commentResponse);

        if($commentResponse){

            // slack comment
            if($leadDetail->slack_channel_name && $leadDetail->slack_channel_name!=""){
                $client = new Client(env('SLACK_WEBHOOK'));
                // $client->to('#ubunanny')->send('I am sending message from Laravel Slack');
                //$client->from('CRM')->to('#ubunanny')->withIcon(':ghost:')->send('Adventure time!');
                $slackUserName = $commentResponse->user->name;
                if($commentResponse->user->employeeInfo && $commentResponse->user->employeeInfo->slack_username && $commentResponse->user->employeeInfo->slack_username!=""){
                    $slackUserName = $commentResponse->user->employeeInfo->slack_username;
                }
                $responseSlack = $client->from("CRM: ".$slackUserName)->to($commentResponse->lead->slack_channel_name)->send($comment);
            }
            // slack comment end here
            $response = PRResponse::getSuccessResponse("Comment submitted successfully",array());
        }
        return $response;
    }
    public function getLeadEmployeeAssignment($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadEmployeeAssignmentCollection($leadId);
        $toReturnArray = [];
        $leadEmployeeDto = new LeadQCEmployeeDTO();
        foreach($leadEmployeeOrmCollection as $tempLeadEmployee){
            array_push($toReturnArray,$leadEmployeeDto->convertToDTO($tempLeadEmployee));
        }
        return $toReturnArray;
    }
    public function getLeadQcEmployeeAssignment($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadQcEmployeeAssignmentCollection($leadId);
        $toReturnArray = [];
        $leadEmployeeDto = new LeadEmployeeDTO();
        foreach($leadEmployeeOrmCollection as $tempLeadEmployee){
            array_push($toReturnArray,$leadEmployeeDto->convertToDTO($tempLeadEmployee));
        }
        return $toReturnArray;
    }
    public function getLeadFieldEmployeeAssignment($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadFieldEmployeeAssignmentCollection($leadId);
        $toReturnArray = [];
        $leadEmployeeDto = new LeadEmployeeDTO();
        foreach($leadEmployeeOrmCollection as $tempLeadEmployee){
            array_push($toReturnArray,$leadEmployeeDto->convertToDTO($tempLeadEmployee));
        }
        return $toReturnArray;
    }
    public function getLeadVendorAssignment($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadVendorAssignmentCollection($leadId);
        $toReturnArray = [];
        $leadEmployeeDto = new LeadVendorDTO();
        foreach($leadEmployeeOrmCollection as $tempLeadEmployee){
            array_push($toReturnArray,$leadEmployeeDto->convertToDTO($tempLeadEmployee));
        }
        return $toReturnArray;
    }
    public function getLeadStatusChangeList($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadStatusChangeLog($leadId);
        $toReturnArray = [];
        $leadStatusDto = new LeadStatusDTO();
        foreach($leadEmployeeOrmCollection as $tempStatus){
            array_push($toReturnArray,$leadStatusDto->convertToDTO($tempStatus));
        }
        return $toReturnArray;
    }

    public function getLeadVendorAttendance($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getLeadVendorAttendanceLog($leadId);
        $toReturnArray = [];
        $leadVADto = new LeadVendorAttendanceDTO();
        foreach($leadEmployeeOrmCollection as $tempStatus){
            array_push($toReturnArray,$leadVADto->convertToDTO($tempStatus));
        }
        return $toReturnArray;
    }

    public function getCustomerClosureRequest($leadId){
        $leadEmployeeOrmCollection = $this->operationDomainService->getStatusChangeRequestByCustomer($leadId);
        $toReturnArray = [];
        $leadChangeRequestDto = new CustomerStatusRequestDTO();
        foreach($leadEmployeeOrmCollection as $tempStatus){
            array_push($toReturnArray,$leadChangeRequestDto->convertToDTO($tempStatus));
        }
        return $toReturnArray;
    }

    public function getCustomerNotifications($leadId){
        $leadCustomerNotification = $this->operationDomainService->getCustomerNotifications($leadId);
        $toReturnArray = [];
        $customerNotificationDto = new CustomerNotificationDTO();
        foreach($leadCustomerNotification as $tempNotification){
            array_push($toReturnArray,$customerNotificationDto->convertToDTO($tempNotification));
        }
        return $toReturnArray;
    }

    public function getVendorAttendanceByCustomer($leadId){
        $vendorAttendanceByCustomer = $this->operationDomainService->getVendorAttendanceByCustomer($leadId);
        $toReturnArray = [];
        $vendorAttendanceDto = new CustomerVendorAttendanceDTO();
        foreach($vendorAttendanceByCustomer as $tempAttendance){
            array_push($toReturnArray,$vendorAttendanceDto->convertToDTO($tempAttendance));
        }
        return $toReturnArray;
    }

    public function getVendorStatusByCustomer($leadId){
        $vendorStatusByCustomer = $this->operationDomainService->getVendorStatusByCustomer($leadId);
        $toReturnArray = [];
        $vendorAttendanceDto = new CustomerVendorStatusDTO();
        foreach($vendorStatusByCustomer as $tempAttendance){
            array_push($toReturnArray,$vendorAttendanceDto->convertToDTO($tempAttendance));
        }
        return $toReturnArray;
    }

    public function getLeadLogs($leadId){
        $leadCommentDtoList = $this->getLeadComments($leadId);
        $leadEmployeeDtoList = $this->getLeadEmployeeAssignment($leadId);
        $leadVendorDtoList = $this->getLeadVendorAssignment($leadId);

        $leadQcEmployeeDtoList = $this->getLeadQcEmployeeAssignment($leadId);
        $leadFieldEmployeeDtoList = $this->getLeadFieldEmployeeAssignment($leadId);


        $leadStatusesDtoList = $this->getLeadStatusChangeList($leadId);


        $vendorAttendanceLog = $this->getLeadVendorAttendance($leadId);


        // customer app services
        $customerClosureRequest = $this->getCustomerClosureRequest($leadId);

        $customerNotificationList = $this->getCustomerNotifications($leadId);

        $customerVendorAttendance = $this->getVendorAttendanceByCustomer($leadId);
        $customerVendorStatuses = $this->getVendorStatusByCustomer($leadId);

        $leadComplaintLogs = $this->getComplaintLogs($leadId);




        $mergedLogList = array();
        // TODO: merging list
        foreach($leadCommentDtoList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('comment');
            $leadLogDto->setTaskUser($tempDtoItem->user);
            $leadLogDto->setTaskUserId($tempDtoItem->userId);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($leadEmployeeDtoList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('employee_assignment');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->assignedBy);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($leadQcEmployeeDtoList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('qc_assignment');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->assignedBy);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($leadFieldEmployeeDtoList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('field_assignment');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->assignedBy);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($leadVendorDtoList as $tempDtoItem){
            //d($tempDtoItem);
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('vendor_assignment');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->assignedBy);
            array_push($mergedLogList,$leadLogDto);
        }
        foreach($leadStatusesDtoList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('status_change');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->user);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($vendorAttendanceLog as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('vendor_attendance');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->user);

            array_push($mergedLogList,$leadLogDto);
        }


        foreach($customerClosureRequest as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('customer_status_change_request');
            $leadLogDto->setTaskUserId(null);
            $leadLogDto->setTaskUser(null);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($customerNotificationList as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('customer_notification');
            $leadLogDto->setTaskUserId($tempDtoItem->userId);
            $leadLogDto->setTaskUser($tempDtoItem->user);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($customerVendorAttendance as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('customer_vendor_attendance');
            $leadLogDto->setTaskUserId(null);
            $leadLogDto->setTaskUser(null);

            array_push($mergedLogList,$leadLogDto);
        }
        foreach($customerVendorStatuses as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('customer_vendor_status');
            $leadLogDto->setTaskUserId(null);
            $leadLogDto->setTaskUser(null);

            array_push($mergedLogList,$leadLogDto);
        }

        foreach($leadComplaintLogs as $tempDtoItem){
            $leadLogDto = new LeadLogDTO();
            $leadLogDto->setDateTime($tempDtoItem->dateTime);
            $leadLogDto->setData($tempDtoItem);
            $leadLogDto->setTaskType('complaint_log');
            $leadLogDto->setTaskUser($tempDtoItem->user);
            $leadLogDto->setTaskUserId($tempDtoItem->userId);

            array_push($mergedLogList,$leadLogDto);
        }



        // Sorting the log items
        usort($mergedLogList,function($a,$b){
            return $a->dateTime>$b->dateTime;
        });

        return $mergedLogList;
    }

    public function getAssignableEmployees(){
        $employeeListAssignable = $this->operationDomainService->getAssignableEmployeesCollection();
        $usersListOrm = $employeeListAssignable->users;
        $userInfoDto = new UserInfoDTO();
        $toReturnList = [];
        foreach ($usersListOrm as $tempUser) {
            array_push($toReturnList,$userInfoDto->convertToDto($tempUser));
        }
        return $toReturnList;
    }
    public function assignEmployeeToLead($leadId,$assigneeId,$userId){
        $leadDetailedObject = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $commentResponse = $this->operationDomainService->assignEmployeeToLead($leadId,$assigneeId,$userId);
        $response = PRResponse::getErrorResponse('Unable to assign employee to lead, please try after sometime.',$commentResponse);
        if($commentResponse){
            $this->mailHelperService->sendMailOnEmployeeAssignment($leadDetailedObject,$assigneeId);
            $userAssigned = $commentResponse;
            $userInfoDto = new UserInfoDTO();
            $response = PRResponse::getSuccessResponse("User assigned successfully",$userInfoDto->convertToDto($userAssigned));
        }

        if(env('SLACK_NOTIFICATION')==('enabled')){
            $this->slackHelperService->employeeAssignedNotification($leadDetailedObject,$this->userDomainService->getUser($assigneeId));

        }

        $this->checkAllAssignmentAndSendMail($leadDetailedObject);

        return $response;
    }
    public function assignQcEmployeeToLead($leadId,$assigneeId,$userId){

        $leadDetailedObject = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $leadQcAssigned = $leadDetailedObject->qcAssigned->last();
        if( $leadQcAssigned  && $leadQcAssigned->assignee_user_id == $assigneeId){
            // already assigned to same
            $response = PRResponse::getWarningResponse('Unable to assign employee, already assigned.',array());
            return $response;
        }

        $commentResponse = $this->operationDomainService->assignQcEmployeeToLead($leadId,$assigneeId,$userId);
        $response = PRResponse::getErrorResponse('Unable to assign employee to lead, please try after sometime.',$commentResponse);
        if($commentResponse){
            // send mail to new  QC assigned
            $this->mailHelperService->sendMailOnQcAssignment($leadDetailedObject,$assigneeId);
            $this->mailHelperService->sendMailForFieldAssignment($leadDetailedObject);

            $userAssigned = $commentResponse;
            $userInfoDto = new UserInfoDTO();



            $response = PRResponse::getSuccessResponse("User assigned successfully",$userInfoDto->convertToDto($userAssigned));
        }


        if(env('SLACK_NOTIFICATION')==('enabled')){
            $this->slackHelperService->qcAssignedNotification($leadDetailedObject,$this->userDomainService->getUser($assigneeId));

            /*$assignedUserOrm = $this->userDomainService->getUser($assigneeId);
            $userMinimalDto = new UserMinimalDTO();
            $userMinimalDto = $userMinimalDto->convertToDto($assignedUserOrm);
            $client = new Client(env('SLACK_WEBHOOK'));
            $responseSlack = $client->to($leadDetailedObject->slack_channel_name)
                ->attach([
                    'fallback' => 'Assigned to validate Caregiver to project',
                    'text' => 'Assigned to validate Caregiver to project',
                    'author_name' => ucfirst($userMinimalDto->getName()),
                    'author_link' => url('lead/'.$leadId),
                    'title' => 'Assigned as QC',
                    'title_link'=>url('lead/'.$leadId),
                    'author_icon' => $userMinimalDto->getImageUrl()
                ])->send();*/
        }
        $this->checkAllAssignmentAndSendMail($leadDetailedObject);


        return $response;
    }
    public function assignFieldEmployeeToLead($leadId,$assigneeId,$userId){

        $leadDetailedObject = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $leadQcAssigned = $leadDetailedObject->fieldAssigned->last();
        if( $leadQcAssigned  && $leadQcAssigned->assignee_user_id == $assigneeId){
            // already assigned to same
            $response = PRResponse::getWarningResponse('Unable to assign employee, already assigned.',array());
            return $response;
        }

        $commentResponse = $this->operationDomainService->assignFieldEmployeeToLead($leadId,$assigneeId,$userId);
        $response = PRResponse::getErrorResponse('Unable to assign employee to lead, please try after sometime.',$commentResponse);
        if($commentResponse){
            // send mail to new  QC assigned
            $this->mailHelperService->sendMailOnFieldAssignment($leadDetailedObject,$assigneeId);
            $this->mailHelperService->sendMailForCustomerAboutFieldAssignment($leadDetailedObject,$assigneeId);


            $userAssigned = $commentResponse;
            $userInfoDto = new UserInfoDTO();

            $response = PRResponse::getSuccessResponse("User assigned successfully",$userInfoDto->convertToDto($userAssigned));
        }
        if(env('SLACK_NOTIFICATION')==('enabled')){
            $this->slackHelperService->fieldAssignedNotification($leadDetailedObject,$this->userDomainService->getUser($assigneeId));
            /*$assignedUserOrm = $this->userDomainService->getUser($assigneeId);
            $userMinimalDto = new UserMinimalDTO();
            $userMinimalDto = $userMinimalDto->convertToDto($assignedUserOrm);
            $client = new Client(env('SLACK_WEBHOOK'));
            $responseSlack = $client->to($leadDetailedObject->slack_channel_name)
                ->attach([
                    'fallback' => 'Assigned for Field Operations to project',
                    'text' => 'Assigned for Field Operations to project',
                    'author_name' => ucfirst($userMinimalDto->getName()),
                    'author_link' => url('lead/'.$leadId),
                    'title' => 'Assigned as Field Executive',
                    'title_link'=>url('lead/'.$leadId),
                    'author_icon' => $userMinimalDto->getImageUrl()
                ])->send();*/
        }
        $this->checkAllAssignmentAndSendMail($leadDetailedObject);
        return $response;
    }
    public function assignVendorToLead($leadId,$assigneeId,$pricePerDay,$isPrimary,$sourcingData,$userId){
        $commentResponse = $this->operationDomainService->assignVendorToLead($leadId,$assigneeId,$pricePerDay, $isPrimary,$userId, $sourcingData);
        $response = PRResponse::getErrorResponse('Unable to assign employee to lead, please try after sometime.',$commentResponse);
        $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);
        if($commentResponse){

            $userAssigned = $commentResponse;
            $userInfoDto = new UserInfoDTO();
            $response = PRResponse::getSuccessResponse("Vendor assigned successfully",$userInfoDto->convertToDto($userAssigned));


            // mail to qc manager to verify QC and verify assigned CG
            $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);
            $this->mailHelperService->sendMailOnCGAssignment($leadDetailedOrm);
        }

        $this->notificationRestService->submitCustomerNotificationAboutCGAssignment($leadId);

        if(env('SLACK_NOTIFICATION')==('enabled')){


            $this->slackHelperService->cgAssignedNotification($leadDetailedOrm,$this->userDomainService->getUser($assigneeId),$isPrimary);


            /*$slackMessage ="";
            if($isPrimary){
                $slackMessage = "Assigned as primary caregiver to the project.";
            }else{
                $slackMessage = "Assigned as backup caregiver to the project.";
            }
            $assignedUserOrm = $this->userDomainService->getUser($assigneeId);
            $userMinimalDto = new UserMinimalDTO();
            $userMinimalDto = $userMinimalDto->convertToDto($assignedUserOrm);
            $client = new Client(env('SLACK_WEBHOOK'));
            $responseSlack = $client->to($leadDetailedOrm->slack_channel_name)
                ->attach([
                    'fallback' => $slackMessage,
                    'text' => $slackMessage,
                    'author_name' => ucfirst($userMinimalDto->getName()),
                    'author_link' => url('lead/'.$leadId),
                    'title' => 'Assigned as '.($isPrimary?'primary':'backup').' caregiver.',
                    'title_link'=>url('lead/'.$leadId),
                    'author_icon' => $userMinimalDto->getImageUrl()
                ])->send();*/
        }
        $this->checkAllAssignmentAndSendMail($leadDetailedOrm);
        return $response;
    }

    public function submitQcBriefing($leadId,$qcAssignmentId,$briefingData,$userId){
        $response = $this->operationDomainService->submitQcBriefingDetails($leadId,$qcAssignmentId,$briefingData,$userId);
        if($response){
            return PRResponse::getSuccessResponse("Successfully submitted briefing details.", array());
        }
        return PRResponse::getErrorResponse("Unable to submit briefing details",array());
    }
    public function getOperationalStatusesGrouped(){
        $groupListOrm = $this->operationDomainService->getOperationalStatusesGroupList();
        $groupListDto = array();
        $operationalGroupDto = new OperationalStatusGroupDTO();
        foreach ($groupListOrm as $tempGroupItem) {
            array_push($groupListDto,$operationalGroupDto->convertToDTO($tempGroupItem));
        }

        return $groupListDto;
    }

    public function updateLeadStatus($leadId,$inputAll,$userId){
        $statusUpdateResponse = false;
        $userUpdateResponse = false;
        $commentUpdateStatus = false;
        $reasonId = null;
        $statusComment = "";
        $statusData='';
        if(isset($inputAll['reasonId'])){
            $reasonId = $inputAll['reasonId'];
        }
        if(isset($inputAll['comment'])){
            $statusComment = $inputAll['comment'];
        }
        $statusDate = null;

        if(isset($inputAll['date'])){
            $cDate = Carbon::parse($inputAll['date']);
            $statusDate = $cDate;
        }
        if(isset($inputAll['data'])){
            $tempStatusData = $inputAll['data'];
            if(isset($tempStatusData['date']) && $tempStatusData['date']){
                $newDateData = Carbon::now();

                $newDateData->timestamp(strtotime($tempStatusData['date']));
                $newDateData->second = 0;
                $tempStatusData['date'] = $newDateData;
            }
            $statusData = $tempStatusData;
        }
        if(isset($inputAll['statusId'])){
            // TODO: update status
            $statusUpdateResponse = $this->operationDomainService->updateLeadStatus($leadId,$inputAll['statusId'],$userId, $reasonId,$statusComment,$statusData,$statusDate);
        }
        if(isset($inputAll['userId'])){
            // TODO: update assignee user
            $userUpdateResponse = $this->operationDomainService->assignEmployeeToLead($leadId,$inputAll['userId'],$userId);
        }else{
            $userUpdateResponse = true;
        }
        if(isset($inputAll['comment']) && trim($inputAll['comment'])!=""){
            // TODO: update assignee user
            $commentUpdateStatus = $this->operationDomainService->submitLeadComment($leadId,$inputAll['comment'],$userId);
        }else{
            $commentUpdateStatus = true;
        }

        // deduction of CG
        if(isset($inputAll['deduction']) && trim($inputAll['deduction'])!=0){
            $vendorInfo = $this->operationDomainService->getLatestPrimaryVendorByLead($leadId);

            if($vendorInfo){
                $incentive = new Incentives;
                $incentive->user_id = $vendorInfo->id;
                $incentive->amount =  -1 * abs(trim($inputAll['deduction']));
                $incentive->type = 'deduction';
                $incentive->comment = 'Service Closed';
                $incentive->lead_id = $leadId;
                $incentive->is_active = 0;

                if(isset($inputAll['date'])){
                    $cDate = Carbon::parse($inputAll['date']);
                    $incentive->date = $cDate->toDateString();
                } else {
                    $dateRaw = Carbon::now();
                    $incentive->date = $dateRaw->toDateString();
                }
                $incentive->save();
            }
        }
        
        // data update for lead
        $statusChangedOrm = $this->operationDomainService->getStatusBySlug('postponed');
        if($statusChangedOrm && $statusChangedOrm->id == $inputAll['statusId']){
            // change start date
            $this->operationDomainService->updateLeadStartDate($leadId,$inputAll['data']['date']);
        }

        if($commentUpdateStatus && $statusUpdateResponse && $userUpdateResponse){
            $responseDataObject = array();
            if(isset($userUpdateResponse) && isset($userUpdateResponse->id)){
                $userInfoDto = new UserInfoDTO();
                $responseDataObject['userAssigned'] = $userInfoDto->convertToDto($userUpdateResponse);
            }
            if($statusUpdateResponse){
                $operationalStatusDto = new OperationalStatusDTO();
                $responseDataObject['stautusUpdated'] = $operationalStatusDto->convertToDTO($statusUpdateResponse);
            }
            return PRResponse::getSuccessResponse("Status updated successfully.",$responseDataObject);
        }
        return PRResponse::getErrorResponse("Unknown error occured. Please contact system administrator",array());

    }


    public function approveLead($leadId,$inputAll,$userId){
        $statusUpdateResponse = false;
        $userUpdateResponse = false;
        $commentUpdateStatus = false;

        $statusUpdateResponse = $this->operationDomainService->approveSalesLead($leadId,$userId);
        if(isset($inputAll['userId'])&& $inputAll['userId'] != null){
            // TODO: update assignee user
            $userUpdateResponse = $this->operationDomainService->assignEmployeeToLead($leadId,$inputAll['userId'],$userId);
        }else{
            $userUpdateResponse = true;
        }
        if(isset($inputAll['comment']) && trim($inputAll['comment'])!=""){
            // TODO: update assignee user
            $commentUpdateStatus = $this->operationDomainService->submitLeadComment($leadId,$inputAll['comment'],$userId);
        }else{
            $commentUpdateStatus = true;
        }
        if($commentUpdateStatus && $statusUpdateResponse && $userUpdateResponse){
            $responseDataObject = array();
            if(isset($userUpdateResponse) && isset($userUpdateResponse->id)){
                $userInfoDto = new UserInfoDTO();
                $responseDataObject['userAssigned'] = $userInfoDto->convertToDto($userUpdateResponse);
            }
            if($statusUpdateResponse){
                $operationalStatusDto = new OperationalStatusDTO();
                $responseDataObject['stautusUpdated'] = $operationalStatusDto->convertToDTO($statusUpdateResponse);
            }
            return PRResponse::getSuccessResponse("Successfully updated lead successfully.",$responseDataObject);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());

    }

    public function getPaymentType(){
        $ormList = PaymentType::get();
        $returnList = array();
        $paymentTypeDto = new PaymentTypeDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$paymentTypeDto->convertToDTO($tempOrm));
        }
        return $returnList;
    }
    public function getPaymentPeriod(){
        $ormList = PaymentPeriod::get();
        $returnList = array();
        $paymentPeriodDto = new PaymentPeriodDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$paymentPeriodDto->convertToDTO($tempOrm));
        }
        return $returnList;

    }
    public function getPaymentMode(){
        $ormList = PaymentMode::get();
        $returnList = array();
        $paymentModeDto = new PaymentModeDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$paymentModeDto->convertToDTO($tempOrm));
        }
        return $returnList;
    }
    public function getPriceUnit(){
        $ormList = PriceUnit::get();
        $returnList = array();
        $priceUnitDto = new PriceUnitDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$priceUnitDto->convertToDTO($tempOrm));
        }
        return $returnList;
    }
    public function getLeadReferences(){
        $ormList = LeadReference::get();
        $returnList = array();
        $leadreferenceDto = new LeadReferenceDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$leadreferenceDto->convertToDTO($tempOrm));
        }
        return $returnList;
    }

    public function getMobilities(){
        $ormList = Mobility::get();
        $returnList = array();
        $mobilityDto = new MobilityDTO();
        foreach($ormList as $tempOrm){
            array_push($returnList,$mobilityDto->convertToDTO($tempOrm));
        }
        return $returnList;
    }

    public function ailmentTasksMapped($serviceId){
        $ailmentDto = new AilmentDTO();
        $ailmentOrmList = $this->operationDomainService->getAilmentTaskByServiceId($serviceId);
        $ailmentDtoList = array();
        foreach($ailmentOrmList as $tempAilment){
            array_push($ailmentDtoList, $ailmentDto->convertToDTO($tempAilment));
        }
        return $ailmentDtoList;
    }

    public function getAilmentList($serviceId)
    {
        $ailmentDto = new AilmentDTO();
        $ailmentList = $this->operationDomainService->getAilmentList($serviceId);
        $returnArray=[];
        foreach($ailmentList as $tempAilment){
            array_push($returnArray,$ailmentDto->convertToDTO($tempAilment) );
        }
        return $returnArray;
    }


    public function updateLeadPatientInfo($leadId,$patientInfo){

        $patientUpdateResponse = $this->operationDomainService->updatePatientInfo($leadId,$patientInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }

    public function updateLeadPatientInfoMobile($leadId,$patientInfo){


        $tempAilment = explode(',',$patientInfo['ailments']);
        $finalAilments = array();
        foreach($tempAilment as $i){
            array_push($finalAilments,array('id'=>$i));
        }
        $patientInfo['ailments']=$tempAilment;



        $patientUpdateResponse = $this->operationDomainService->updatePatientInfo($leadId,$patientInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }


    public function updateLeadPhysioPatientInfo($leadId,$patientInfo){
        $patientUpdateResponse = $this->operationDomainService->updatePhysioPatientInfo($leadId,$patientInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }
    public function updateLeadTaskInfo($leadId,$taskInfo){
        $patientUpdateResponse = $this->operationDomainService->updateTaskInfo($leadId,$taskInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient task information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }
    public function updateLeadTaskInfoMobile($leadId,$taskInfo){

        $tempTasks = explode(',',$taskInfo['tasks']);

        $taskInfo['tasks']=$tempTasks;

        $patientUpdateResponse = $this->operationDomainService->updateTaskInfo($leadId,$taskInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient task information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }

    public function updateLeadSpecialRequest($leadId,$requestInfo){
        $patientUpdateResponse = $this->operationDomainService->updateLeadSpecialRequest($leadId,$requestInfo);
        if($patientUpdateResponse && $patientUpdateResponse->id){
            return PRResponse::getSuccessResponse("Patient special request information updated successfully",$patientUpdateResponse->id);
        }
        return PRResponse::getErrorResponse('Unknown error occured. Please contact system administrator',array());
    }

    public function getValidationTaskCategory(){
        $getTaskCategoryList = $this->operationDomainService->getTaskCategoryWithTask();
        $taskCategoryDto = new TaskCategoryDTO();
        $toReturnArray = array();
        foreach($getTaskCategoryList as $temp){
            array_push($toReturnArray,$taskCategoryDto->convertToDTO($temp));
        }
        return $toReturnArray;
    }

    public function getPatientValidationData($patientId){
        $getTaskCategoryList = $this->operationDomainService->getTaskCategoryWithTask();
        $taskCategoryDto = new TaskCategoryDTO();
        $toReturnArray = array();
        foreach($getTaskCategoryList as $temp){
            array_push($toReturnArray,$taskCategoryDto->convertToDTO($temp));
        }
        $taskMapped = $toReturnArray;

        $patientValidationData = $this->operationDomainService->patientValidationData($patientId);

        $patientValidationDto = new PatientValidationDTO();

        $patientInfo =$this->operationDomainService->getPatientDetail($patientId);

        if($patientInfo && $patientInfo->tasks && $patientInfo->tasks->count()>0){
            $patientValidationDto->setId($patientId);
            $mappedArrForSelectedTask = [];
            foreach($patientInfo->tasks as $itemTask){
                $mappedArrForSelectedTask[$itemTask->id] = true;
            }
            //d($mappedArrForSelectedTask);
            foreach($taskMapped as $tempCat){
                foreach($tempCat->tasks as $taskSingleItem){
                    $taskSingleItem->selected = isset($mappedArrForSelectedTask[$taskSingleItem->id]);
                }
            }
        }
        $patientValidationDto->setTasks($taskMapped);
        return $patientValidationDto;

    }

    public function getPatientValidatedData($patientId){
        $getTaskCategoryList = $this->operationDomainService->getTaskCategoryWithTask();
        $taskCategoryDto = new TaskCategoryDTO();
        $toReturnArray = array();
        foreach($getTaskCategoryList as $temp){
            array_push($toReturnArray,$taskCategoryDto->convertToDTO($temp));
        }
        $taskMapped = $toReturnArray;

        $patientValidationData = $this->operationDomainService->patientValidationData($patientId);

        $patientValidationDto = new PatientValidationDTO();

        $patientInfo =$this->operationDomainService->getPatientDetail($patientId);

        if($patientInfo && $patientInfo->tasks && $patientInfo->tasks->count()>0){
            $patientValidationDto->setId($patientId);
            $mappedArrForSelectedTask = [];
            foreach($patientInfo->tasks as $itemTask){
                $mappedArrForSelectedTask[$itemTask->id] = true;
            }
            //d($mappedArrForSelectedTask);
            foreach($taskMapped as $tempCat){
                foreach($tempCat->tasks as $taskSingleItem){
                    $taskSingleItem->selected = isset($mappedArrForSelectedTask[$taskSingleItem->id]);
                }
            }
        }
        $patientValidationDto->setTasks($taskMapped);
        return $patientValidationDto;

    }

    public function getLeadCount(){
        return Lead::count();
    }

    public function getUnassignedLeadCount(){
        $totalLeadCount = Lead::count();
        $totalAssignedLead = LeadEmployee::distinct()->count();
        return $totalLeadCount-$totalAssignedLead;
    }
    public function getActiveLeadCount(){
        $status = $this->operationDomainService->getStatusBySlug('started');
        return $this->operationDomainService->getLeadCountByStatus($status->id);
    }
    public function uploadPrescription($file,$patientId=null){
        $prescriptionOrm = $this->operationDomainService->uploadPrescription($file,$patientId);
        $patientPrescriptionDto = new PatientPrescriptionDTO();
        if($prescriptionOrm){
            return $patientPrescriptionDto->convertToDto($prescriptionOrm);
        }
        return false;
    }
    
    public function sendMailToCustomer($leadId) {
        $customerMail = $this->operationDomainService->sendMailToCustomer($leadId);
        return $customerMail;
    }

    public function deleteLead($leadId){
        $deleteResponse = $this->operationDomainService->deleteLead($leadId);
        if($deleteResponse){
            return PRResponse::getSuccessResponse('Successfully deleted leads.', array());
        }
        return PRResponse::getErrorResponse('Unable to delete the lead please contact system administrator',array());
    }
    public function deleteBulkLead($leadList){
        $deleteResponse = $this->operationDomainService->deleteBulkLead($leadList);
        if($deleteResponse){
            return PRResponse::getSuccessResponse('Successfully deleted leads.', array());
        }
        return PRResponse::getErrorResponse('Unable to delete the lead please contact system administrator',array());
    }

    public function isAuthorizedToViewContact(){
        return $this->operationDomainService->isAuthorizedToViewContact(Auth::user());

    }

    public function viewLeadContact($leadId){
        // priviledge user
        $userAuthorized = array('nandu@pramaticare.com','vishal@pramaticare.com','mayur@pramaticare.com','neetu@pramaticare.com','divya@pramaticare.com','kripadolldhillion@gmail.com','kripa@pramaticare.com','richa@pramaticare.com','kajal@pramaticare.com');
        array_push($userAuthorized,"mohit2007gupta@gmail.com");
        $authUser = Auth::user();
        if(!in_array($authUser->email,$userAuthorized)){
            return PRResponse::getErrorResponse("You are not authorized to view customer phone number",array());
        }
        return PRResponse::getSuccessResponse("Successfully retrieved phone number.",$this->operationDomainService->getLeadPhoneNumber($leadId));
    }

    private function checkAllAssignmentAndSendMail($leadDetailedOrm){
        if($leadDetailedOrm->employeesAssigned->count()==0){
            return;
        }
        if($leadDetailedOrm->primaryVendorsAssigned->count()==0){
            return;
        }
        if($leadDetailedOrm->qcAssigned->count()==0){
            return;
        }
        if($leadDetailedOrm->fieldAssigned->count()==0){
            return;
        }
        // all assignment done


        if($leadDetailedOrm->customer_assignment_mail_send_at==null){
            Log::info("All assignment done sending mail notification for ".$leadDetailedOrm->id);
            $this->mailHelperService->sendMailToCustomerAboutFieldAssignment($leadDetailedOrm);
            $leadDetailedOrm->customer_assignment_mail_send_at = Carbon::now();
            $leadDetailedOrm->save();
        }
        if($leadDetailedOrm->customer_assignment_sms_send_at==null){
            Log::info("All assignment done sending SMS notification for ".$leadDetailedOrm->id);
            $this->operationDomainService->sendSmsToCustomerAboutFieldAssignment($leadDetailedOrm);
            $leadDetailedOrm->customer_assignment_sms_send_at = Carbon::now();
            $leadDetailedOrm->save();

            $this->notificationRestService->submitCustomerNotificationAboutAllAssignment($leadDetailedOrm->id);
        }
    }

    public function startLeadService($leadId,$inputAll){
        // priviledge user
        $startStatus = $this->operationDomainService->getStatusBySlug('started');
        $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);

        if(isset($inputAll['vendorId']) && $inputAll['vendorId']!=''){
            $vendorId = $inputAll['vendorId'];
            $primaryVendor=null;
            if($leadDetailedOrm->primaryVendorsAssigned->count()>0){
                $primaryVendor = $leadDetailedOrm->primaryVendorsAssigned->last();
            }
            if($primaryVendor && $primaryVendor->id == $vendorId){
                // all fine
            }else{
                $this->operationDomainService->assignVendorToLead($leadId,$vendorId,null,true,Auth::user()->id,array());
                $this->operationDomainService->assignVendorToLead($leadId,0,null,false,Auth::user()->id,array());
            }
        }

        $statusUpdateResponse = $this->operationDomainService->updateLeadStatus($leadId,$startStatus->id,Auth::user()->id, null,null,null, null);

        $this->operationDomainService->markLeadStarted($leadId,null);

        if($statusUpdateResponse && $leadDetailedOrm && $leadDetailedOrm->email!=""){
            //$this->mailHelperService->sendMailToCustomerOnServiceStart($leadDetailedOrm);
            //TODO: uncomment once done
            //$this->notificationRestService->submitCustomerNotificationAboutServiceStart($leadId);
            if(env('SLACK_NOTIFICATION')==('enabled')) {
                $this->slackHelperService->projectStartNotification($leadDetailedOrm);
            }
        }
        if($statusUpdateResponse){
            $this->billingDomainService->generateOrder($leadId, Auth::user()->id);
            return PRResponse::getSuccessResponse("Successfully started lead.",array());
        }
        return PRResponse::getErrorResponse("Unable to start service.",array());
    }


    public function getCarePlanData($leadId){
        $getTaskCategoryList = $this->operationDomainService->getTaskCategoryWithTask();
        $leadDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $taskCategoryDto = new TaskCategoryDTO();
        $toReturnArray = array();
        $patientCarePlanCategoryDtoList = new PatientCarePlanTaskCategoryDTO();

        $patientInfoWithTasks = $this->operationDomainService->getPatientDetail($leadDetailedOrm->patient->id);
        $patientTaskList = $patientInfoWithTasks->tasks;

        $validationTaskList = array();
        foreach($patientTaskList as $tempPatientTask){
            array_push($validationTaskList,$tempPatientTask->id);
        }

        $leadCurrentPrimaryVendor = $this->operationDomainService->getLeadCurrentPrimaryVendor($leadId);
        $primarySourcingTaskList = array();
        if($leadCurrentPrimaryVendor){
            foreach($leadCurrentPrimaryVendor->tasks as $tempTaskObj){
                array_push($primarySourcingTaskList,$tempTaskObj->task_id);
            }
        }



        $leadCurrentBackUpVendor = $this->operationDomainService->getLeadCurrentBackUpVendor($leadId);
        $backUpSourcingTaskList = array();
        if($leadCurrentBackUpVendor){
            foreach($leadCurrentBackUpVendor->tasks as $tempTaskObj){
                array_push($backUpSourcingTaskList,$tempTaskObj->task_id);
            }
        }



        $briefingArray = array();
        $qcBriefingData = $this->operationDomainService->getCurrentAssignedQc($leadId);
        if($qcBriefingData){
            foreach($qcBriefingData->briefingTasks as $tempTaskObj){
                array_push($briefingArray,$tempTaskObj->task_id);
            }
        }

        $primaryEvaluation = array();
        $primaryEvaluationData  = $this->operationDomainService->getCGEvaluationData($leadId,true);
        if($primaryEvaluationData){
            foreach($primaryEvaluationData as $tempTaskObj){
                if($tempTaskObj->evaluation==true){
                    array_push($primaryEvaluation,$tempTaskObj->task_id);
                }
            }
        }

        $backUpEvaluation = array();
        $backUpEvaluationData  = $this->operationDomainService->getCGEvaluationData($leadId,false);
        if($backUpEvaluationData){
            foreach($backUpEvaluationData as $tempTaskObj){
                if($tempTaskObj->evaluation==true){
                    array_push($backUpEvaluation,$tempTaskObj->task_id);
                }
            }
        }

        $cgTrainingMapped = array();
        $cgTrainingData  = $this->operationDomainService->getCGTrainingData($leadId,true);
        if($cgTrainingData){
            foreach($cgTrainingData as $tempTaskObj){
                if($tempTaskObj->training==true){
                    array_push($cgTrainingMapped,$tempTaskObj->task_id);
                }
            }
        }

        $cgCustomerSignOffMapped = array();
        $cgSignOffData  = $this->operationDomainService->getCGCustomerSignOffData($leadId,true);
        if($cgSignOffData){
            foreach($cgSignOffData as $tempTaskObj){
                if($tempTaskObj->sign_off==true){
                    array_push($cgCustomerSignOffMapped,$tempTaskObj->task_id);
                }
            }
        }



        $taskDto = new TaskDTO();
        $toReturn = array();
        foreach($getTaskCategoryList as $tempCat){
            $patientCarePlanCategoryItem = new PatientCarePlanTaskCategoryDTO();
            $catDto = $taskCategoryDto->convertToDTO($tempCat);
            $catDto->tasks = [];
            $taskList = [];
            $patientCarePlanCategoryItem->categoryDto = $catDto;
            foreach($tempCat->tasks as $tempTask){
                $patientCarePlanTaskDto = new PatientCarePlanTaskDTO();
                $patientCarePlanTaskDto->taskInfo = $taskDto->convertToDTO($tempTask);
                if(in_array($tempTask->id,$validationTaskList)){
                    $patientCarePlanTaskDto->validation=true;
                }else{
                    $patientCarePlanTaskDto->validation=false;
                }



                if(count($primarySourcingTaskList)==0){
                    $patientCarePlanTaskDto->primarySourcing=0;
                }else{
                    if(in_array($tempTask->id,$primarySourcingTaskList)){
                        $patientCarePlanTaskDto->primarySourcing=1;
                    }else{
                        $patientCarePlanTaskDto->primarySourcing=-1;
                    }
                }


                if(count($backUpSourcingTaskList)==0){
                    $patientCarePlanTaskDto->backUpSourcing=0;
                }else{
                    if(in_array($tempTask->id,$backUpSourcingTaskList)){
                        $patientCarePlanTaskDto->backUpSourcing=1;
                    }else{
                        $patientCarePlanTaskDto->backUpSourcing=-1;
                    }
                }


                if(count($primaryEvaluation)==0){
                    $patientCarePlanTaskDto->primaryCGEvaluationByQc=0;
                }else{
                    if(in_array($tempTask->id,$primaryEvaluation)){
                        $patientCarePlanTaskDto->primaryCGEvaluationByQc=1;
                    }else{
                        $patientCarePlanTaskDto->primaryCGEvaluationByQc=-1;
                    }
                }

                if(count($backUpEvaluation)==0){
                    $patientCarePlanTaskDto->backUpCGEvaluationByQc=0;
                }else{
                    if(in_array($tempTask->id,$backUpEvaluation)){
                        $patientCarePlanTaskDto->backUpCGEvaluationByQc=1;
                    }else{
                        $patientCarePlanTaskDto->backUpCGEvaluationByQc=-1;
                    }
                }

                if(count($cgTrainingMapped)==0){
                    $patientCarePlanTaskDto->cgTrainingDone=0;
                }else{
                    if(in_array($tempTask->id,$cgTrainingMapped)){
                        $patientCarePlanTaskDto->cgTrainingDone=1;
                    }else{
                        $patientCarePlanTaskDto->cgTrainingDone=-1;
                    }
                }




                if(count($cgCustomerSignOffMapped)==0){
                    $patientCarePlanTaskDto->finalEvaluation=0;
                }else{
                    if(in_array($tempTask->id,$cgCustomerSignOffMapped)){
                        $patientCarePlanTaskDto->finalEvaluation=1;
                    }else{
                        $patientCarePlanTaskDto->finalEvaluation=-1;
                    }
                }





                array_push($taskList,$patientCarePlanTaskDto);
            }
            $patientCarePlanCategoryItem->tasks=$taskList;

            array_push($toReturn,$patientCarePlanCategoryItem);
        }



        return $toReturn;
    }


    public function submitCarePlanEvaluationData($action,$leadId, $data, $userId){
        $response=false;
        if($action == PramatiConstants::CARE_PLAN_ACTION_PRIMARY_EVALUATION){
            $response = $this->operationDomainService->submitCarePlanPrimaryEvaluation($leadId,true,$data, $userId);

        }else if($action == PramatiConstants::CARE_PLAN_ACTION_BACKUP_EVALUATION){
            $response = $this->operationDomainService->submitCarePlanPrimaryEvaluation($leadId,false,$data, $userId);

        }else if($action == PramatiConstants::CARE_PLAN_ACTION_TRAINING_EVALUATION){
            $response = $this->operationDomainService->submitCarePlanTrainingEvaluation($leadId,true,$data, $userId);
        }else if($action == PramatiConstants::CARE_PLAN_ACTION_SIGN_OFF_EVALUATION){
            $response = $this->operationDomainService->submitCarePlanCustomerSignOff($leadId,true,$data, $userId);
        }
        if($response){
            return PRResponse::getSuccessResponse("Successfully updated care plane",array());
        }
        return PRResponse::getErrorResponse("Unable to updated care plane",array());
    }

    public function submitCGAttendance($leadId,$inputAll,$userId){
        $vendorId = null;
        if(isset($inputAll['caregiver']) && isset($inputAll['caregiver']['id'])){
            $vendorId = $inputAll['caregiver']['id'];
        } else {
            //find CG ID
            $leadVendors = $this->operationDomainService->getPrimaryLeadVendorAssignmentCollection($leadId);
            if(!empty($leadVendors)){
                foreach ($leadVendors as $leadVendor) {
                    $vendorId = $leadVendor->assignee_user_id;
                }
            }
        }

        //Find Customer ID
        $customerId = 0;
        


        $comment = $inputAll['comment'];
        $vendorPrice = $inputAll['price'];
        $attendance = $inputAll['attendance'];
        $dateRaw = strtotime($inputAll['date']);
        $attendanceDateCarbon = Carbon::now();
        $attendanceDateCarbon->timestamp($dateRaw);



        $responseObject = $this->operationDomainService->submitCGAttendance($leadId,$vendorId,$attendanceDateCarbon->toDateString(),$attendance,$vendorPrice,$comment,$userId,"CRM");

        //deductions for uninformed leave
        if(isset($inputAll['uninformed'])) {
            if($inputAll['uninformed'] == 1){
                $incentive = new Incentives;
                $incentive->user_id = $vendorId;
                $incentive->amount =  -250;
                $incentive->type = 'deduction';
                $incentive->comment = 'Uninformed Leave';
                $incentive->lead_id = $leadId;
                $incentive->is_active = 0;
                $incentive->date = $attendanceDateCarbon->toDateString();
                $incentive->save();
            }
        }
        
        // automate attendance    
        $customerRequestSubmitRequest = $this->operationDomainService->markCustomerCaregiverAttendance($leadId,$customerId, $attendanceDateCarbon->toDateString(), $attendance,1,1);
    }

    public function submitCGAttendanceBySMS($inputAll){
        //get phone without 91
        $phone = substr($inputAll['mobileno'], -10, 10);
        if($inputAll['keyword'] == "P"){
            $attendance = 1;
        } else {
            $attendance = 0;
        }
        $dateRaw = time();
        $attendanceDateCarbon = Carbon::now();
        $attendanceDateCarbon->timestamp($dateRaw);

        $userOrm = $this->userDomainService->getUserByPhone($phone);
        if($userOrm){
            $userId = $userOrm->id;
        } else {
            return PRResponse::getErrorResponse("No user found with phone.",array());
        }

        if($userOrm->user_type_id == 2){
            // cg

            //find lead for vendor
            $leadVendor = $this->operationDomainService->getLeadIdByVendorId($userId);
            if($leadVendor){
                $leadId = $leadVendor->lead_id;
            } else {
                return PRResponse::getErrorResponse("No Lead Alloted to Vendor.",array());
            }

            $comment = "";
            $vendorPrice = "";

            $responseObject = $this->operationDomainService->submitCGAttendance($leadId,$userId,$attendanceDateCarbon->toDateString(),$attendance,$vendorPrice,$comment,null,"SMS");

            // Add 50 Rs incentive
            $incentive = new Incentives;
            $incentive->user_id = $userId;
            $incentive->amount = 50;
            $incentive->type = 'incentive';
            $incentive->comment = 'Attendance marked by SMS';
            $incentive->lead_id = $leadId;
            $incentive->is_active = 0;
            $incentive->date = $attendanceDateCarbon->toDateString();
            $incentive->save();

        } else if($userOrm->user_type_id == 3){
            // customer

            //find lead for customer
            $leadCustomer = $this->operationDomainService->getLeadIdByCustomerId($userId);
            if($leadCustomer){
                $leadId = $leadCustomer->id;
            } else {
                return PRResponse::getErrorResponse("No service used by customer.",array());
            }

            $customerRequestSubmitRequest = $this->operationDomainService->markCustomerCaregiverAttendance($leadId,$userId, $attendanceDateCarbon->toDateString(), $attendance,1,1);
        }        
        return $inputAll;
    }

    public function getCustomerLeadByUserId($phone){
        $leadOrmList = $this->operationDomainService->getLeadByCustomerPhone($phone);

        $toReturn = array();
        $customerServiceListItemDto = new CustomerServiceListItemDTO();
        foreach($leadOrmList as $tempLead){
            array_push($toReturn,$customerServiceListItemDto->convertToDTO($tempLead));
        }


        return $toReturn;
    }
    public function getLeadDetailForApp($leadId){
        $serviceDetailedOrm = $this->operationDomainService->getLeadDetailedOrm($leadId);
        $CustomerLeadItemDTO = new CustomerLeadItemDTO();
        return $CustomerLeadItemDTO->convertToDTO($serviceDetailedOrm);
    }

    public function getNotificationForUser($userId){
        $userOrm = $this->userDomainService->getUser($userId);
        return $this->notificationRestService->getCustomerNotifications($userOrm->phone);
    }




    public function getLeadClosureOptions(){
        $leadClosureOptions = $this->operationDomainService->getLeadCLosureOptions();
        $operationStatusDto = new OperationalStatusMinimalDTO();

        $toReturnClosureOptions = [];
        foreach($leadClosureOptions as $tempOption){
            array_push($toReturnClosureOptions,$operationStatusDto->convertToDTO($tempOption));
        }
        $closureReason = ($toReturnClosureOptions);

        $closureIssues = [];
        array_push($closureIssues,array(
            'id'=>1,
            'slug'=>'cg-skill-issue',
            'label'=> 'CG Skill Issue'
        ));
        array_push($closureIssues,array(
            'id'=>2,
            'slug'=>'cg-behaviour-issue',
            'label'=> 'CG Behaviour Issue'
        ));
        array_push($closureIssues,array(
            'id'=>3,
            'slug'=>'backend-issue',
            'label'=> 'Backend Issue'
        ));
        array_push($closureIssues,array(
            'id'=>4,
            'slug'=>'other-issue',
            'label'=> 'Other Issue'
        ));


        return array(
            'closureReason'=>$closureReason,
            'closureIssues'=>$closureIssues
        );



    }


    public function submitLeadClosureRequest($inputAll){

        Log::info(json_encode($inputAll));
        if(!isset($inputAll['lead_id'])){
            return PRResponse::getErrorResponse("Lead id is required",(object) array());
        }
        if(!isset($inputAll['option_id'])){
            return PRResponse::getErrorResponse("Option id is required",(object) array());
        }
        if(!isset($inputAll['reason_id'])){
            return PRResponse::getErrorResponse("Reason is required",(object) array());
        }
        $leadId = $inputAll['lead_id'];
        $closureStatusId = $inputAll['option_id'];
        $closureReasonId = $inputAll['reason_id'];

        if(!isset($inputAll['issue_id'])){
            $closureIssueId = null;
        }else{
            $closureIssueId = $inputAll['issue_id'];
        }
        if(!isset($inputAll['other'])){
            $closureOtherReason = '';
        }else{
            $closureOtherReason = $inputAll['other'];
        }


        $customerId = Authorizer::getResourceOwnerId();

        $customerRequestSubmitRequest = $this->operationDomainService->markCustomerClosureRequest($leadId,$customerId, $closureStatusId,$closureReasonId,$closureIssueId,$closureOtherReason);



        if($customerRequestSubmitRequest){

            $customerRequestDto = new CustomerStatusRequestDTO();
            $customerRequestDtoConverted = $customerRequestDto->convertToDto($customerRequestSubmitRequest);
            if(env('SLACK_NOTIFICATION')==('enabled')) {
                $this->slackHelperService->projectClosureRequestNotification($this->operationDomainService->getLeadDetailedOrm($leadId), $customerRequestDtoConverted);
            }

            return PRResponse::getSuccessResponse("Successfully marked cg status",(object) array());
        }

        return PRResponse::getErrorResponse("Unable to marked request",(object) array());
    }

    public function submitVendorNotReachedStatus($inputAll){
        if(!isset($inputAll['lead_id'])){
            return PRResponse::getErrorResponse("Lead id is required",(object) array());
        }
        $leadId = $inputAll['lead_id'];

        if(isset($inputAll['comment'])){
            $comment = $inputAll['comment'];
        }else{
            $comment="";
        }





        $customerId = Authorizer::getResourceOwnerId();
//lead_customer_vendor_status
        $customerRequestSubmitRequest = $this->operationDomainService->submitVendorNotReachedStatus($leadId,$customerId, "VENDOR_NOT_REACHED", $comment);



        if($customerRequestSubmitRequest){

            if(env('SLACK_NOTIFICATION')==('enabled')) {

                $this->slackHelperService->cgNotReachedNotification($this->operationDomainService->getLeadDetailedOrm($leadId), $customerRequestSubmitRequest);
            }
            return PRResponse::getSuccessResponse("Successfully marked request",(object) array());
        }

        return PRResponse::getErrorResponse("Unable to marked status",(object) array());
    }

    public function leadRestartRequest($customerId,$leadId){
        $customerRequestSubmitRequest = $this->operationDomainService->submitLeadRestartRequestFromCustomer($customerId,$leadId);

        if($customerRequestSubmitRequest){
            if(env('SLACK_NOTIFICATION')==('enabled')) {
                $customerStatusRequestDto = new CustomerStatusRequestDTO();
                $customerStatusRequest = $customerStatusRequestDto->convertToDto($customerRequestSubmitRequest);
                $this->slackHelperService->projectRestartRequestNotification($this->operationDomainService->getLeadDetailedOrm($leadId), $customerStatusRequest);
            }
            return PRResponse::getSuccessResponse("Successfully marked request",(object) array());
        }

        return PRResponse::getErrorResponse("Unable to marked restart request",(object) array());
    }
    public function submitCustomerFeedback($customerId,$inputAll){
        $customerId = Authorizer::getResourceOwnerId();
        if(!isset($inputAll['rating'])){
            return PRResponse::getErrorResponse("Please provide rating to server you better",(object) array());
        }

        $submitResponse = $this->operationDomainService->submitCustomerFeedback($customerId,$inputAll['rating'],$inputAll['behaviour_id'],$inputAll['comment']);

        if($submitResponse){
            return PRResponse::getSuccessResponse("Feedback submitted successfully.",(object) array());
        }

        return PRResponse::getErrorResponse("Unable to submit feedback.",(object) array());

    }
    public function submitCustomerCaregiverAttendance($inputAll){

        if(!isset($inputAll['lead_id'])){
            return PRResponse::getErrorResponse("Lead id is required",(object) array());
        }
        if(!isset($inputAll['is_present'])){
            return PRResponse::getErrorResponse("Attendance parameter is required",(object) array());
        }
        if(!isset($inputAll['on_time'])){
            return PRResponse::getErrorResponse("On time parameter is required",(object) array());
        }
        if(!isset($inputAll['well_dressed'])){
            return PRResponse::getErrorResponse("Well dressed parameter is required",(object) array());
        }
        $leadId = $inputAll['lead_id'];
        $isPresent = $inputAll['is_present'];
        $isOnTime = $inputAll['on_time'];
        $isWellDressed = $inputAll['well_dressed'];


        $dateRaw = strtotime($inputAll['date']);
        $attendanceDateCarbon = Carbon::now();
        $attendanceDateCarbon->timestamp($dateRaw);




        $customerId = Authorizer::getResourceOwnerId();

        $customerRequestSubmitRequest = $this->operationDomainService->markCustomerCaregiverAttendance($leadId,$customerId, $attendanceDateCarbon->toDateString(), $isPresent,$isOnTime,$isWellDressed);




        if($customerRequestSubmitRequest){
            if(env('SLACK_NOTIFICATION')==('enabled')) {
                $this->slackHelperService->customerSubmittedCaregiverAttendanceNotification($this->operationDomainService->getLeadDetailedOrm($leadId), $customerRequestSubmitRequest);
            }

            return PRResponse::getSuccessResponse("Successfully marked cg attendance",(object) array());
        }

        return PRResponse::getErrorResponse("Unable to marked attendance",(object) array());
    }


    public function getActiveProjectGridList(){
        $toReturn = [];
        $activeLeads  = $this->operationDomainService->getActiveLeads();

        $authorized = $this->isAuthorizedToViewContact();
        $activeProjectDto = new ActiveProjectGridItemDTO();
        foreach($activeLeads as $tempLead){
            $dto = $activeProjectDto->convertToDTO($tempLead);
            if(!$authorized){
                $dto->setPhone("xxxxxxxxxx");
            }
            array_push($toReturn,$dto);
        }

        return $toReturn;
    }

    public function getVendorAttendanceReport($date){
        $carbonDate = Carbon::parse($date);
        $resultList = $this->operationDomainService->getVendorAttendanceOnDate($carbonDate->toDateString());
        $toReturn = array();
        //print_r($resultList);exit;

        $activeLeadsOnDate = $this->operationDomainService->getActiveLeadOnDate($carbonDate->toDateString());

        $attendanceMapper = array();
        $activeLeadAttendanceDto = new ActiveLeadAttendanceDTO();

        $customerVendorAttendanceDto = new CustomerVendorAttendanceDTO();

        $vendorIncentiveDTO = new VendorIncentiveDTO();

        $leadVendorAttendanceDto = new LeadVendorAttendanceDTO();

        foreach($activeLeadsOnDate as $tempDates){

            //For NA
            $tempDates->vendorAttendance->vendor = $this->operationDomainService->getLatestPrimaryVendorByLead($tempDates->lead_id);
            $tempDates->vendorAttendance->medium = "";

            $tempDto = $activeLeadAttendanceDto->convertToDTODetailed($tempDates);

            // temp fix (lead_id in app is 0)
            $vendorIncentive = (object) [];
            if(isset($tempDates->vendorAttendance->vendor_id)){
                $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$tempDates->vendorAttendance->vendor_id)->first();
            } else if (isset($tempDates->vendorAttendance->vendor->assignee_user_id)){
                $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$tempDates->vendorAttendance->vendor->assignee_user_id)->first();
            }
            $tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($vendorIncentive));

            if(isset($resultList[$tempDates->lead_id])){

                if(empty($resultList[$tempDates->lead_id]['crm'])){
                    // fake vendor attendance object
                    $fakeObj = (object) [];
                    $fakeObj->id = '';
                    $fakeObj->is_present = 'NA';
                    $fakeObj->price = '';
                    $fakeObj->date = '';
                    $fakeObj->comment = '';
                    $fakeObj->lead_id = $tempDates->lead_id;
                    $fakeObj->created_at = '';
                    $fakeObj->marked_by = '';
                    $fakeObj->medium = '';
                    $fakeObj->user = '';
                    $fakeObj->vendor = $tempDates->vendorAttendance->vendor;

                    $resultList[$tempDates->lead_id]['crm'] = $fakeObj;
                }
                //print_r($resultList['1308']['crm']);
                //print_r($resultList[$tempDates->lead_id]['crm']->id.'-'.$tempDates->lead_id.'     '); 

                if(!empty($resultList[$tempDates->lead_id]['crm'])){
                    $tempDto->setVendorAttendance($leadVendorAttendanceDto->convertToDTO($resultList[$tempDates->lead_id]['crm']));
                }

                $tempDto->setCustomerVendorAttendance($customerVendorAttendanceDto->convertToDto($resultList[$tempDates->lead_id]['customer']));

                // permanent
                //$tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($resultList[$tempDates->lead_id]['vendorIncentive']));
                
                // temp fix (lead_id in app is 0)
                /*if(isset($resultList[$tempDates->lead_id]['crm']->vendor_id)){ 
                    $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$resultList[$tempDates->lead_id]['crm']->vendor_id)->first();
                } else {
                    $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$resultList[$tempDates->lead_id]['crm']->vendor->assignee_user_id)->first();
                }
                $tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($vendorIncentive));*/

            }


            if(trim($tempDto->getCustomerName())!=""){
                array_push($toReturn,$tempDto);
            }

        }
        return $toReturn;
        die();

        foreach($resultList as $date){

        }
        return ($toReturn);
    }


    public function syncLeadSlackComment($leadId){

        $leadOrm = $this->operationDomainService->getLeadDetail($leadId);
        if($leadOrm->slack_channel_name=="" || $leadOrm->slack_channel_created_at ==null){
            return;
        }
        $slackChannelName = trim($leadOrm->slack_channel_name);
        $allChannels = SlackChannel::all();
        $channelId = null;
        foreach($allChannels->channels as $channel){
            if($channel->name == strtolower($slackChannelName)){
                $channelId=$channel->id;
                break;
            }
        }
        //echo $channelId;
        $lastSlackMessage = LeadComment::where('lead_id','=',$leadId)->orderBy('slack_timestamp','desc')->first();

        if($channelId){
            $messageHistoryList = SlackChannel::history($channelId);
            if(!$messageHistoryList->messages || count($messageHistoryList->messages)==0){
                return;
            }
            $toPushSlackMessage = [];
            foreach($messageHistoryList->messages as $slackMsg){
                ////////d($slackMsg);
                if($lastSlackMessage && $slackMsg->ts<$lastSlackMessage->slack_timestamp){
                    continue;
                }
              //  d($slackMsg);
                if(isset($slackMsg->subtype) && $slackMsg->subtype!=null){
                    continue;
                }
                if(isset($slackMsg->bot_id) && $slackMsg->bot_id!=null){
                    continue;
                }
                array_push($toPushSlackMessage,$slackMsg);
            }

//            d($toPushSlackMessage);
            foreach($toPushSlackMessage as $tempSlack){
                $slackCommentExist = LeadComment::whereRaw('slack_user = ? and slack_timestamp = ?', array($tempSlack->user, $tempSlack->ts))->count();
                if($slackCommentExist>0){
                    continue;
                }
                $leadComment = new LeadComment();
                $leadComment->lead_id = $leadId;
                $leadComment->comment = $tempSlack->text;
                $carbonTimeStamp = Carbon::now();
                $carbonTimeStamp->timestamp = $tempSlack->ts;
                $leadComment->created_at = $carbonTimeStamp;
                $leadComment->is_from_slack = true;
                $leadComment->slack_timestamp = $tempSlack->ts;
                $leadComment->slack_user = $tempSlack->user;
                $leadComment->save();
            }

        }

    }

    public function getPendingCGAssignmentNotification(){
        return $this->operationDomainService->getPendingCGAssignmentNotification();
    }

    public function connectLeadTableToUser(){
        $leadOrmList = $this->operationDomainService->getLeadListNullUserId();

        $queryTimes = 0;
        foreach ($leadOrmList as $leadOrm) {
            
            // register user

            $phone = $leadOrm->phone;
            $email = $leadOrm->email;

            $userByPhone = $this->userDomainService->getUserByPhone($phone);
            $customerByPhone = $this->userDomainService->getCustomerByPhone($phone);
            /*$userByEmail=null;
            if($email!=''){
                $userByEmail = $this->userDomainService->getUserByEmail($email);
                if($userByPhone && $userByEmail && $userByPhone->id != $userByEmail->id){
                    return PRResponse::getErrorResponse("User with same email already exist",(object)array());
                }
            }*/


            if(!$userByPhone && !$customerByPhone){
                $createCustomerByData = array(
                    'phone' => $phone,
                    'name' => $leadOrm->customer_name,
                    'email' => $email
                );
                $userByPhone = $this->userDomainService->createCustomerByData($createCustomerByData);
            }else if(!$userByPhone && $customerByPhone){
                $userByPhone = $this->userDomainService->createCustomerByPhone($customerByPhone,$phone);
            }else if($userByPhone && $customerByPhone){
                $this->userDomainService->updateCustomerPhoneOnLead($userByPhone->id,$phone,false);
            }

            // ./register user

            $queryTimes++;
        }
        echo 'QueryTimes => '.$queryTimes.'    ';
        return PRResponse::getSuccessResponse("Done",array('QueryTimes'=>$queryTimes));
    }

    public function getAllotableEmployeesComplaints(){
        $employeeListAssignable = $this->operationDomainService->getAllotableEmployeesComplaintsCollection();
        $usersListOrm = $employeeListAssignable->users;
        $userInfoDto = new UserInfoDTO();
        $toReturnList = [];
        foreach ($usersListOrm as $tempUser) {
            array_push($toReturnList,$userInfoDto->convertToDto($tempUser));
        }
        return $toReturnList;
    }

    public function getCurrentVendor($leadId){
        $cgInfo = $this->operationDomainService->getLatestVendorByLead($leadId);
        if(!isset($cgInfo->id)){
            return 'No Vendor Found.';
        }
        $userInfoDto = new UserInfoDTO();
        return $userInfoDto->convertToDto($cgInfo);
    }

    public function cronTester($vendorId){
        /*$vendorDomainService = new VendorDomainService();
        $vendors = $vendorDomainService->getVendorList()->reverse();
        
        foreach ($vendors as $vendor) {
            
            $presentCount = $this->operationDomainService->getPresentCount($vendor->user_id);
            $workingDaysCount = $this->operationDomainService->getWorkingDaysCount($vendor->user_id);
            $absentCount = $workingDaysCount - $presentCount;

        }*/

        //$vendorId = 4230;

        $workingDaysCount = $this->operationDomainService->getWorkingDaysCount($vendorId);
        echo 'Working Days: '.$workingDaysCount;

        // to prevent division by zero
        if($workingDaysCount != 0){
            $presentCount = $this->operationDomainService->getPresentCount($vendorId);
            echo '<br>Present Days: '.$presentCount;
            echo '<br>Present %: '.$this->percentage($presentCount,$workingDaysCount);

            $absentCount = $workingDaysCount - $presentCount;
            echo '<br>Absent Days: '.$absentCount;
            echo '<br>Absent %: '.$this->percentage($absentCount,$workingDaysCount);

            $uninformedAbsents = $this->operationDomainService->getVendorComplaintCountByType($vendorId, 75);
            echo '<br>Uninformed Absents: '.$uninformedAbsents;
            echo '<br>Uninformed Leaves %: '.$this->percentage($uninformedAbsents,$absentCount);
        }

        $punctualityComplaintCount = $this->operationDomainService->getVendorComplaintCountByType($vendorId, 26);
        echo '<br>Punctuality Complaints: '.$punctualityComplaintCount;

        $prePlacementComplaintsCount = $this->operationDomainService->getVendorComplaintCountByType($vendorId, 9);
        echo '<br>Pre Placement Complaints: '.$prePlacementComplaintsCount;


        // new code

        $skillsComplaintCount = $this->operationDomainService->getVendorComplaintCountByType($vendorId, 18);
        echo '<br>Skills Complaints: '.$skillsComplaintCount;

        $totalComplaints = $this->operationDomainService->getVendorComplaintCount($vendorId);
        echo '<br>Total Complaints: '.$totalComplaints;

        $crmAttendanceCount = $this->operationDomainService->getVendorAttendanceByMediumCount($vendorId,'CRM');
        echo '<br>CRM Attendance: '.$crmAttendanceCount;

        $replacementRequestsCount = $this->operationDomainService->getReplacementRequestsByVendorCount($vendorId);
        echo '<br>Replacement Requests: '.$replacementRequestsCount;
        

        exit;
        return PRResponse::getSuccessResponse("Done",array());
    }

    public function percentage($value, $total){
        return number_format((($value/$total) * 100), 2);
    }

    public function getSalaryByPeriodReport($dateFrom,$dateTo,$showMode){
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);
        $difference = $dateTo->diffInDays($dateFrom);
        $return = array();

        if($showMode == 'CG'){
            $return = $this->operationDomainService->getSalaryByPeriodReportCGWise($dateFrom->toDateString(),$dateTo->toDateString(),$difference);
        } else if($showMode == 'Customer'){
            $return = $this->operationDomainService->getSalaryByPeriodReportCustomerWise($dateFrom->toDateString(),$dateTo->toDateString());
        }
        return $return;
    }

    public function getSalaryByPeriodReport_old($dateFrom,$dateTo,$showMode){
        $carbonDateFrom = Carbon::parse($dateFrom);
        $carbonDateTo = Carbon::parse($dateTo);
        $return = array();

        $vendorDomainService = new VendorDomainService();
        $vendors = $vendorDomainService->getVendorList();

        $mailCount = 0;

        if(!empty($vendors)){
            foreach ($vendors as $vendor) {

                $usedLeads = array();
                $leadRangeDates = array();
                $leadRangeDatesVendor = array();

                $vendorId = $vendor->user_id;
                $return[$mailCount]['vendorInfo'] = $vendor;

                $vendorLeads = $this->operationDomainService->getLeadsForVendor($vendorId);
                if(!empty($vendorLeads)){
                    
                    foreach ($vendorLeads as $vendorLead) {
                        if(!in_array($vendorLead->lead_id, $usedLeads)){
                            $usedLeads[] = $vendorLead->lead_id;

                            $workingDays = 0;
                            $mapper = array();

                            $leadInnerCount = 0;
                            $return[$mailCount][$leadInnerCount]['leadInfo'] = $vendorLead;

                            $minDate = '';
                            $maxDate = '';

                            $leadActiveDates = LeadActiveDate::where('lead_id','=',$vendorLead->lead_id)->get();

                            if(count($leadActiveDates) != 0){
                                $lastActiveDate = $leadActiveDates->last()->active_date;


                                $leadVendors = $this->operationDomainService->getVendorsForLead($vendorLead->lead_id);
                                if(!empty($leadVendors)){
                                
                                    $tempFlag = false;

                                    for($count = 1; $count <= count($leadVendors); $count ++){

                                        $minDate = Carbon::parse(Carbon::parse($leadVendors[$count - 1]->created_at)->toDateString())->timestamp;

                                        if(isset($leadVendors[$count])){
                                            $maxDate = Carbon::parse(Carbon::parse($leadVendors[$count]->created_at)->subDay()->toDateString())->timestamp;
                                        } else {
                                            $maxDate = Carbon::parse(Carbon::parse($lastActiveDate)->toDateString())->timestamp;
                                        }

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

                                }

                                for($i=0;$i<count($leadRangeDates);$i++) {
                                    $mapper[$leadRangeDates[$i]] = $leadRangeDatesVendor[$i];
                                }

                                if(!empty($leadActiveDates)){
                                    foreach ($leadActiveDates as $leadActiveDate) {
                                        if(isset($mapper[$leadActiveDate->active_date])){
                                            if(($mapper[$leadActiveDate->active_date]) == $vendorId){
                                                $workingDays++;
                                            }
                                        }
                                    }
                                }

                            }

                            $return[$mailCount][$leadInnerCount]['workingDays'] = $workingDays;
                            $leadInnerCount++;
                        }
                    }
                }

                $mailCount++;
            }
        }

        print_r(json_encode($return));
        exit;

        return $workingDays;

























        $resultList = $this->operationDomainService->getVendorAttendanceOnDate($carbonDate->toDateString());
        $toReturn = array();
        //print_r($resultList);exit;

        $activeLeadsOnDate = $this->operationDomainService->getActiveLeadOnDate($carbonDate->toDateString());

        $attendanceMapper = array();
        $activeLeadAttendanceDto = new ActiveLeadAttendanceDTO();

        $customerVendorAttendanceDto = new CustomerVendorAttendanceDTO();

        $vendorIncentiveDTO = new VendorIncentiveDTO();

        $leadVendorAttendanceDto = new LeadVendorAttendanceDTO();

        foreach($activeLeadsOnDate as $tempDates){

            //For NA
            $tempDates->vendorAttendance->vendor = $this->operationDomainService->getLatestPrimaryVendorByLead($tempDates->lead_id);
            $tempDates->vendorAttendance->medium = "";

            $tempDto = $activeLeadAttendanceDto->convertToDTODetailed($tempDates);

            // temp fix (lead_id in app is 0)
            $vendorIncentive = (object) [];
            if(isset($tempDates->vendorAttendance->vendor_id)){
                $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$tempDates->vendorAttendance->vendor_id)->first();
            } else if (isset($tempDates->vendorAttendance->vendor->assignee_user_id)){
                $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$tempDates->vendorAttendance->vendor->assignee_user_id)->first();
            }
            $tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($vendorIncentive));

            if(isset($resultList[$tempDates->lead_id])){

                if(empty($resultList[$tempDates->lead_id]['crm'])){
                    // fake vendor attendance object
                    $fakeObj = (object) [];
                    $fakeObj->id = '';
                    $fakeObj->is_present = 'NA';
                    $fakeObj->price = '';
                    $fakeObj->date = '';
                    $fakeObj->comment = '';
                    $fakeObj->lead_id = $tempDates->lead_id;
                    $fakeObj->created_at = '';
                    $fakeObj->marked_by = '';
                    $fakeObj->medium = '';
                    $fakeObj->user = '';
                    $fakeObj->vendor = $tempDates->vendorAttendance->vendor;

                    $resultList[$tempDates->lead_id]['crm'] = $fakeObj;
                }
                //print_r($resultList['1308']['crm']);
                //print_r($resultList[$tempDates->lead_id]['crm']->id.'-'.$tempDates->lead_id.'     '); 

                if(!empty($resultList[$tempDates->lead_id]['crm'])){
                    $tempDto->setVendorAttendance($leadVendorAttendanceDto->convertToDTO($resultList[$tempDates->lead_id]['crm']));
                }

                $tempDto->setCustomerVendorAttendance($customerVendorAttendanceDto->convertToDto($resultList[$tempDates->lead_id]['customer']));

                // permanent
                //$tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($resultList[$tempDates->lead_id]['vendorIncentive']));
                
                // temp fix (lead_id in app is 0)
                /*if(isset($resultList[$tempDates->lead_id]['crm']->vendor_id)){ 
                    $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$resultList[$tempDates->lead_id]['crm']->vendor_id)->first();
                } else {
                    $vendorIncentive = Incentives::where('date','=',$carbonDate->toDateString())->where('user_id','=',$resultList[$tempDates->lead_id]['crm']->vendor->assignee_user_id)->first();
                }
                $tempDto->setVendorIncentive($vendorIncentiveDTO->convertToDto($vendorIncentive));*/

            }


            if(trim($tempDto->getCustomerName())!=""){
                array_push($toReturn,$tempDto);
            }

        }
        return $toReturn;
        die();

        foreach($resultList as $date){

        }
        return ($toReturn);
    }


}