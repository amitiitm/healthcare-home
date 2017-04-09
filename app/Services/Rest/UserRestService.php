<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Rest\IUserRestContract;
use App\Models\DTO\UserGridItemDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\Enums\SCConstants;
use App\Models\User;
use App\Templates\PRResponse;
use Illuminate\Support\Facades\Auth;

class UserRestService implements IUserRestContract
{
    protected $userDomainService;

    public function __construct(IUserDomainContract $userDomainService)
    {
        $this->userDomainService = $userDomainService;
    }

    public function getUserGridList(){
        $userOrmList = $this->userDomainService->getUserList();

        $returnList = array();
        $userMinimalDto = new UserGridItemDTO();
        foreach($userOrmList as $tempUser){
            array_push($returnList,$userMinimalDto->convertToDTO($tempUser));
        }
        return $returnList;
    }

    public function getVendorGridList(){
        $userOrmList = $this->userDomainService->getUserList();

        $returnList = array();
        $vendorGridItemDto = new VendorGridItemDTO();
        foreach($userOrmList as $tempUser){
            array_push($returnList,$vendorGridItemDto->convertToDTO($tempUser));
        }
        return $returnList;
    }

    public function addUser($inputAll){
        $userType = $inputAll['type'];
        $name = $inputAll['name'];
        $email = $inputAll['email'];
        $phone = $inputAll['phone'];
        $password = $inputAll['password'];

        // check already registered
        $userCountByEmail = $this->userDomainService->getUserByEmail($email);
        if($userCountByEmail>0){
            return PRResponse::getErrorResponse("Email address is already registered",$email);
        }
        $userAddResponse = $this->userDomainService->addUser($userType,$name,$email,$password,$phone);
        if($userAddResponse){
            $userMinimalDto = new UserMinimalDTO();
            return PRResponse::getSuccessResponse("User registered",$userMinimalDto->convertToDto($userAddResponse));

        }
    }



    /// below are waste for pramaticare
    public function getCurrentUser()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getUserWithLevels($user->id);
        }
        return null;
    }

    public function getUser($id)
    {
        return $this->userDomainService->getUserWithLevels($id);
    }

    public function makeAdmin($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_admin_privilege) {
            return $this->userDomainService->changeLevel($id, SCConstants::ADMIN);
        }
        return null;
    }

    public function removeAdmin($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_admin_privilege) {
            return $this->userDomainService->changeLevel($id, SCConstants::REGULAR);
        }
        return null;
    }

    public function makeModerator($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_moderator_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::MODERATOR);
        }
        return null;
    }

    public function removeModerator($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_moderator_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::REGULAR);
        }
        return null;
    }

    public function makeEditor($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_editor_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::EDITOR);
        }
        return null;
    }

    public function removeEditor($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_editor_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::REGULAR);
        }
        return null;
    }

    public function makeAuthor($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_author_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::AUTHOR);
        }
        return null;
    }

    public function removeAuthor($id)
    {
        $user = Auth::user();
        if ($user->level()->first()->can_grant_revoke_author_privilege and $user->level()->first()->id >= $this->getUser($id)->id) {
            return $this->userDomainService->changeLevel($id, SCConstants::REGULAR);
        }
        return null;
    }

    public function getModerators()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getModerators();
        }
        return null;
    }

    public function getAuthors()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getModerators();
        }
        return null;
    }

    public function getEditors()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getEditors();
        }
        return null;
    }

    public function getAdministrators()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getAdministrators();
        }
        return null;
    }

    public function getCurrentUserContributions()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getUserContributions($user->id);
        }
        return null;
    }

    public function getCurrentUserArticles()
    {
        $user = Auth::user();
        if ($user) {
            return $this->userDomainService->getUserContributions($user->id);
        }
        return null;    
    }

    public function getUserArticles($id)
    {
        return $this->userDomainService->getUserArticles($id);
    }




    // for mobile app
    public function sendLoginOtp($phone){
        $userByPhone = $this->userDomainService->getUserByPhone($phone);
        if(!$userByPhone){
            return PRResponse::getErrorResponse('Unknown user',array());
        }
        $otpSendResponse = $this->userDomainService->sendOtpForLogin($userByPhone);
        return PRResponse::getSuccessResponse('OTP send to your mobile',array());
        //return PRResponse::getWarningResponse('Error while sending OTP to the user.',array());
    }
    public function sendCustomerLoginOtp($phone){
        $userByPhone = $this->userDomainService->getUserByPhone($phone);
        $customerByPhone = $this->userDomainService->getCustomerByPhone($phone);
        if(!$userByPhone && !$customerByPhone){
            return PRResponse::getErrorResponse('Unknown user',array());
        }else if(!$userByPhone && $customerByPhone){
            $userByPhone = $this->userDomainService->createCustomerByPhone($customerByPhone,$phone);
        }else if($userByPhone && $customerByPhone){
            $this->userDomainService->updateCustomerPhoneOnLead($userByPhone->id,$phone);
        }
        $otpSendResponse = $this->userDomainService->sendOtpForLogin($userByPhone);
        return PRResponse::getSuccessResponse('OTP send to your mobile',array());
        //return PRResponse::getWarningResponse('Error while sending OTP to the user.',array());
    }
    public function sendCustomerSignUpOtp($inputAll){

        if(!isset($inputAll['phone'])){
            return PRResponse::getErrorResponse("Phone is required",(object)array());
        }
        $phone = $inputAll['phone'];
        if(isset($inputAll['email'])){
            $email = $inputAll['email'];
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
            $userByPhone = $this->userDomainService->createCustomerByData($inputAll);
        }else if(!$userByPhone && $customerByPhone){
            $userByPhone = $this->userDomainService->createCustomerByPhone($customerByPhone,$phone);
        }else if($userByPhone && $customerByPhone){
            $this->userDomainService->updateCustomerPhoneOnLead($userByPhone->id,$phone);
        }
        $otpSendResponse = $this->userDomainService->sendOtpForLogin($userByPhone);
        return PRResponse::getSuccessResponse('OTP send to your mobile',array());
        //return PRResponse::getWarningResponse('Error while sending OTP to the user.',array());
    }

    public function getUserProfile($userId){
        $userOrm = User::where('id','=',$userId)->first();
        $userMinimalDto = new UserMinimalDTO();
        return $userMinimalDto->convertToDto($userOrm);
    }

    public function registerDeviceToken($userId,$deviceToken){
        return $this->userDomainService->registerDeviceToken($userId,$deviceToken);
    }
}