<?php

namespace App\Http\Controllers\API;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Models\DTO\AilmentDTO;
use App\Models\DTO\Mobile\CaregiverLeadItemDTO;
use App\Models\DTO\Mobile\CaregiverLeadListItemDTO;
use App\Models\ORM\Agency;
use App\Models\ORM\Gender;
use App\Models\ORM\Lead;
use App\Models\ORM\LocationZone;
use App\Models\ORM\Qualification;
use App\Models\ORM\VendorSource;
use App\Models\User;
use App\Models\ORM\LeadVendor;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Carbon\Carbon;


class UserAPIController extends Controller
{
    protected $operationRestService;

    protected $commonRestServices;

    protected $userRestServices;

    public function __construct( IOperationRestContract $operationRestService, ICommonRestContract $commonRestContract, IUserRestContract $userRestContract)
    {
        $this->operationRestService = $operationRestService;
        $this->commonRestServices = $commonRestContract;
        $this->userRestServices = $userRestContract;
    }

    public function getLeadListForCG(){

    }


    public function loginWithOtp(){
        $issuedAccessToken = Authorizer::issueAccessToken();
        return Response::json($issuedAccessToken);
    }


    public function postLoginOtpRequest(){
        $phoneNumber = Input::get('mobile');
        $this->userRestServices->sendLoginOtp($phoneNumber);
    }
    public function postCustomerLoginOtpRequest(){
        $phoneNumber = Input::get('mobile');
        $this->userRestServices->sendCustomerLoginOtp($phoneNumber);
    }
    public function postCustomerSignUpRequest(){
        $this->userRestServices->sendCustomerSignUpOtp(Input::all());
    }


    public function userProfile(){
        $userId = Authorizer::getResourceOwnerId();
        return Response::json(PRResponse::getSuccessResponse('',$this->userRestServices->getUserProfile($userId)));
    }

    public function registerDevice(){
        $userId = Authorizer::getResourceOwnerId();
        return Response::json(PRResponse::getSuccessResponse('Successfully registered device',$this->userRestServices->registerDeviceToken($userId,Input::get('device_token'))));
    }

    public function getUserServicesList(){
        $userId = Authorizer::getResourceOwnerId();
        $userServicesList = array();
        $userOrm = $this->userRestServices->getUser($userId);
        $userServicesList = $this->operationRestService->getCustomerLeadByUserId($userOrm->phone);
        return Response::json(PRResponse::getSuccessResponse('',$userServicesList));
    }

    public function getUserServiceDetail($leadId){
        $leadDetailDto = $this->operationRestService->getLeadDetailForApp($leadId);
        return Response::json(PRResponse::getSuccessResponse("",$leadDetailDto));
    }

    public function getUserNotification(){
        $userId = Authorizer::getResourceOwnerId();
        $notificationList = $this->operationRestService->getNotificationForUser($userId);
        return Response::json(PRResponse::getSuccessResponse("",$notificationList));
    }

    public function getSearchByName($query){
        //$userOrm = $this->userRestServices->getUser($userId);
        $data = User::where('name','LIKE', $query.'%')->get();
        return Response::json(PRResponse::getSuccessResponse("",$data));
    }

    public function getUserLeads($userId){
        //$userOrm = $this->userRestServices->getUser($userId);

        $leadsVendor = LeadVendor::where('assignee_user_id',$userId)
            ->orderBy('created_at','desc')
            ->get();

        $userOrm = $this->userRestServices->getUser($userId);

        $leadsCustomer = Lead::where('user_id',$userId)
            ->orWhere('phone',$userOrm->phone)
            ->orderBy('created_at','desc')
            ->get();

        $data = array();
        $counter = 0;
        if(!empty($leadsVendor)){
            foreach ($leadsVendor as $row) {
                if($row->lead_id){
                    $data[$counter]['lead_id'] = $row->lead_id;
                    $dt = Carbon::parse($row->created_at);
                    $data[$counter]['created_at'] = $dt->toDateTimeString();
                    $counter++;
                }
            }
        }
        if(!empty($leadsCustomer)){
            foreach ($leadsCustomer as $row) {
                if($row->id){
                    $data[$counter]['lead_id'] = $row->id;
                    $dt = Carbon::parse($row->created_at);
                    $data[$counter]['created_at'] = $dt->toDateTimeString();
                    $counter++;
                }
            }
        }

        return Response::json(PRResponse::getSuccessResponse("",$data));

        /*$userServicesList = array();
        $userOrm = $this->userRestServices->getUser($userId);
        $userServicesList = $this->operationRestService->getCustomerLeadByUserId($userOrm->phone);
        return Response::json(PRResponse::getSuccessResponse('',$userServicesList));*/
    }




}