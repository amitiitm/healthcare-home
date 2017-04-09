<?php

namespace App\Services\Rest;

use App\Contracts\Domain\INotificationDomainContract;
use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IFireBasePushHelperContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Contracts\Helper\ISMSHelperContract;
use App\Contracts\Rest\INotificationRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Models\DTO\AilmentDTO;
use App\Models\DTO\Careplan\PatientCarePlanTaskCategoryDTO;
use App\Models\DTO\Careplan\PatientCarePlanTaskDTO;
use App\Models\DTO\CustomerNotificationDTO;
use App\Models\DTO\EnquiryGridItemDTO;
use App\Models\DTO\GridDataDTO;
use App\Models\DTO\LeadCommentDTO;
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

class NotificationRestService implements INotificationRestContract
{
    protected $operationDomainService;

    protected $mailHelperService;

    protected $userDomainService;

    protected $slackHelperService;

    protected $notificationDomainService;

    protected $fireBasePushHelperService;

    protected $smsHelperService;


    public function __construct(IOperationDomainContract $operationDomainService,
                                IMailHelperContract $mailHelperContract,
                                IUserDomainContract $IUserDomainContract,
                                ISlackHelperContract $ISlackHelperContract,
                                INotificationDomainContract $INotificationDomainContract,
                                IFireBasePushHelperContract $IFireBasePushHelperContract,
                                ISMSHelperContract $ISMSHelperContract
    )
    {
        $this->operationDomainService = $operationDomainService;

        $this->mailHelperService = $mailHelperContract;

        $this->userDomainService = $IUserDomainContract;

        $this->slackHelperService = $ISlackHelperContract;

        $this->notificationDomainService = $INotificationDomainContract;

        $this->fireBasePushHelperService = $IFireBasePushHelperContract;

        $this->smsHelperService = $ISMSHelperContract;
    }

    public function getCustomerNotificationTemplates(){
        $notificationOrmList =$this->notificationDomainService->getNotificationTemplates();


        $notificationTemplatesDTOList = array();



        foreach ($notificationOrmList as $template) {
            array_push($notificationTemplatesDTOList,$template);
        }

        return PRResponse::getSuccessResponse('',$notificationTemplatesDTOList);


    }

    public function submitCustomerNotificationAboutAllAssignment($leadId){
        $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
        $phoneNo = $leadDetail->phone;

        $loggedInUser = Auth::user()->id;

        $header = "Pramati Care";
        $content = "Dear Customer, Processing of your request is complete. Sit back and relax, your service will start at scheduled time. Please check your service details for more information";

        $deviceTokenForPhone = $this->notificationDomainService->getDeviceTokenByPhone($phoneNo);

        $notificationOrm = $this->notificationDomainService->submitCustomerNotification($leadId,$phoneNo,$header,$content,$loggedInUser);
        $smsResponse =$this->smsHelperService->sendSMS($phoneNo,$content);

        if(count($deviceTokenForPhone)>0){
            // has smart phone with app installed
            $pushResponse = $this->fireBasePushHelperService->sendPushNotification($deviceTokenForPhone,$header,$content);
            Log::info("PUSH Response ---->");
            Log::info(json_encode($pushResponse));
            //d($pushResponse);
//            $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }
    }

    public function submitCustomerNotificationAboutServiceStart($leadId){
        $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
        $phoneNo = $leadDetail->phone;

        $loggedInUser = Auth::user()->id;

        $header = "Pramati Care";
        $content = "Thanks for choosing Pramaticare, your service is active now. Please check service details for more information.";
        $deviceTokenForPhone = $this->notificationDomainService->getDeviceTokenByPhone($phoneNo);

        $notificationOrm = $this->notificationDomainService->submitCustomerNotification($leadId,$phoneNo,$header,$content,$loggedInUser);

        $smsResponse =$this->smsHelperService->sendSMS($phoneNo,$content);
        if(count($deviceTokenForPhone)>0){
            // has smart phone with app installed
            $pushResponse = $this->fireBasePushHelperService->sendPushNotification($deviceTokenForPhone,$header,$content);
            Log::info("PUSH Response ---->");
            Log::info(json_encode($pushResponse));
            //d($pushResponse);
//            $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }

    }
    public function submitCustomerNotificationAboutCGAssignment($leadId){
        $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
        $phoneNo = $leadDetail->phone;

        $loggedInUser = Auth::user()->id;

        $header = "Pramati Care";
        $content = "Dear Customer, the service provider has been assigned for your service. Please check the App for more details.";

        $deviceTokenForPhone = $this->notificationDomainService->getDeviceTokenByPhone($phoneNo);

        $notificationOrm = $this->notificationDomainService->submitCustomerNotification($leadId,$phoneNo,$header,$content,$loggedInUser);


        if(count($deviceTokenForPhone)>0){
            // has smart phone with app installed
            $pushResponse = $this->fireBasePushHelperService->sendPushNotification($deviceTokenForPhone,$header,$content);
            Log::info("PUSH Response ---->");
            Log::info(json_encode($pushResponse));
            //d($pushResponse);
//            $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }else{
            // send SMS
            $smsResponse =$this->smsHelperService->sendSMS($phoneNo,$content);
            //          $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }
    }

    public function submitCustomerNotification($leadId, $notificationObject){
        $leadDetail = $this->operationDomainService->getLeadDetail($leadId);
        $phoneNo = $leadDetail->phone;

        $deviceTokenForPhone = $this->notificationDomainService->getDeviceTokenByPhone($phoneNo);

        $loggedInUser = Auth::user()->id;

        $notificationOrm = $this->notificationDomainService->submitCustomerNotification($leadId,$phoneNo,$notificationObject['header'],$notificationObject['content'],$loggedInUser);

        if(isset($notificationObject['new_template']) && $notificationObject['new_template']==true && isset($notificationObject['new_template_name']) && $notificationObject['new_template_name']!=""){
            // save new template
            $this->notificationDomainService->createNewTemplate($notificationObject['new_template_name'],$notificationObject['header'],$notificationObject['content']);
        }

        if(!$notificationOrm){
            return PRResponse::getErrorResponse("Unable to send notification",(object)array());
        }
        //d($deviceTokenForPhone);
        $smsResponse =$this->smsHelperService->sendSMS($phoneNo,$notificationObject['content']);
        if(count($deviceTokenForPhone)>0){
            // has smart phone with app installed
            $pushResponse = $this->fireBasePushHelperService->sendPushNotification($deviceTokenForPhone,$notificationObject['header'],$notificationObject['content']);
            Log::info("PUSH Response ---->");
            Log::info(json_encode($pushResponse));
            //d($pushResponse);
            $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }else{
            // send SMS
            //$smsResponse =$this->smsHelperService->sendSMS($phoneNo,$notificationObject['content']);
  //          $this->notificationDomainService->markCustomerNotificationPushSent($notificationOrm->id,Carbon::now());
        }
        echo "-----------------";
        //$smsResponse =$this->smsHelperService->sendSMS($phoneNo,$notificationObject['content']);


        Log::info($smsResponse);
        return PRResponse::getSuccessResponse("Customer notified",$notificationOrm->id);
    }


    public function getCustomerNotifications($phone){
        $customerNotificationList = $this->notificationDomainService->getCustomerNotification($phone);
        $toReturn = array();

        $customerNotificationDto = new CustomerNotificationDTO();

        foreach($customerNotificationList as $tempNotification){
            array_push($toReturn,$customerNotificationDto->convertToDto($tempNotification));
        }

        return $toReturn;

    }

}