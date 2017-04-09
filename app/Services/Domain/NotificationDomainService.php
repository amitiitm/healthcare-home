<?php

namespace App\Services\Domain;

use App\Contracts\Domain\INotificationDomainContract;
use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\AgeRange;
use App\Models\ORM\Complaint;
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
use App\Models\ORM\LeadApprovalEscalation;
use App\Models\ORM\LeadComment;
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
use App\Models\ORM\NotificationTemplate;
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

class NotificationDomainService implements INotificationDomainContract
{

    public function getNotificationTemplates(){
        return NotificationTemplate::all();
    }

    public function getDeviceTokenByPhone($phoneNo){
        $getUserIdListByPhone = User::where('phone','=',$phoneNo)->with('deviceTokens')->get();
        $toReturnDeviceToken = [];
        //d($getUserIdListByPhone);
        foreach($getUserIdListByPhone as $tempUser){

            if($tempUser->deviceTokens && $tempUser->deviceTokens->count()>0){
                array_push($toReturnDeviceToken,$tempUser->deviceTokens->last()->device_token);
            }
        }
//        d($toReturnDeviceToken);
        return $toReturnDeviceToken;
    }

    public function submitCustomerNotification($leadId, $phoneNo, $header, $content,$loggedInUser){
        $notificationObject = new CustomerNotification();
        $notificationObject->lead_id = $leadId;
        $notificationObject->customer_phone = $phoneNo;
        $notificationObject->header = $header;
        $notificationObject->content = $content;
        $notificationObject->user_id = $loggedInUser;
        $notificationObject->save();
        return $notificationObject;
    }

    public function createNewTemplate($templateName, $header, $content){
        $notificationObject = new NotificationTemplate();
        $notificationObject->label = $templateName;
        $notificationObject->header = $header;
        $notificationObject->content = $content;
        $notificationObject->save();
        return $notificationObject;
    }
    public function markCustomerNotificationPushSent($notificationId, $timeStamp){
        $notificationObject = CustomerNotification::where('id','=',$notificationId)->first();
        $notificationObject->push_sent_at = $timeStamp;
        $notificationObject->save();
        return $notificationObject;
    }
    public function markCustomerNotificationSMSSent($notificationId, $timeStamp){
        $notificationObject = CustomerNotification::where('id','=',$notificationId);
        $notificationObject->sms_sent_at = $timeStamp;
        $notificationObject->save();
        return $notificationObject;
    }

    public function getCustomerNotification($phone){
        return CustomerNotification::where('customer_phone','=',$phone)->orderBy('created_at','desc')->get();
    }

}