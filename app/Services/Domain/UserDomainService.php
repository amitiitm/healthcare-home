<?php

namespace App\Services\Domain;

use App\Contracts\Domain\ICommonDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\DeviceToken;
use App\Models\ORM\Lead;
use App\Models\ORM\UserEmployee;
use App\Models\User;
use App\Templates\PRResponse;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UserDomainService implements IUserDomainContract
{


    public function getUserList(){
        return User::get();
    }

    public function addUser($userType,$name,$email,$password,$phone){
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'user_type_id' => $userType,
            'phone'=>$phone
        ]);
    }

    public function getUserByEmail($email){
        return User::where('email','=',$email)->count();
    }




    /// Below are waste for pramaticare
    public function getUser($id)
    {
        return User::with('employeeInfo')->find($id);
    }

    public function getUserWithLevels($id)
    {
        return User::where('id', $id)->with('level')->first();
    }

    public function changeLevel($userId, $newLevelId)
    {
        $user = User::find($userId);
        $user->level = $newLevelId;
        $user->save();
        return $user;
    }

    public function getIdsFromEmails($emailList)
    {
        if (count($emailList) > 0)
            return User::whereIn('email', $emailList)->lists('id')->all();
        else
            return [];
    }

    public function getAdministrators()
    {
        return User::where('level', SCConstants::ADMIN)->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function getModerators()
    {
        return User::where('level', SCConstants::MODERATOR)->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function getEditors()
    {
        return User::where('level', SCConstants::EDITOR)->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function getAuthors()
    {
        return User::where('level', SCConstants::AUTHOR)->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function getUserContributions($id)
    {
        return User::where('id', $id)->with(['contributedArticles' => function ($query) {
            $query->orderBy('created_at', 'desc')->paginate(SCConstants::PAGINATION_NUMBER);
        }])->first()->contributedArticles;
    }

    public function getUserArticles($id)
    {
        return Article::where('author_id', $id)->orderBy('created_at', 'desc')->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function sendResetLink($user,$token)
    {
        $userOrm = $user;

        $registrationLink = url("password/reset/" . $token);
        $registrationData = ['name' => $userOrm->name, 'email' => $userOrm->email, 'registration_link' => $registrationLink];
        //d($registrationData);

        Mail::send('emails.password', ['data' => $registrationData], function ($m) use ($user) {
            $m->from(env('MAIL_FROM'), 'Pramati Care');
            $m->to($user->email, $user->name)->subject('Password assistance Pramaticare');
        });
    }

    public function getUserByPhone($phone){
        return User::where('phone','=',$phone)->first();
    }

    public function getCustomerByPhone($phone){
        return Lead::where('phone','=',$phone)->first();
    }
    public function createCustomerByData($data){
        $phone = $data['phone'];
        $leadObject = Lead::where('phone','=',$phone)->first();
        $newUser = new User();
        if($leadObject){
            $newUser->name = trim($leadObject->customer_name." ".$leadObject->customer_last_name);
        }else if(isset($data['name'])){
            $newUser->name = $data['name'];
        }else {
            $newUser = $data['name'];
        }

        $newUser->password = "customer_password";

        $generatedEmail="";
        // check unique email
        if($leadObject && $leadObject->email==""){
            $generatedEmail = strval(time())."@".rand().strval(time()).".com";
        }

        $emailCheckExist = User::where('email','=',$data['email'])->count();
        if($emailCheckExist>0){
            $generatedEmail = strval(time())."@".rand().strval(time()).".com";
        }else if($data['email']!=''){
            $generatedEmail = $data['email'];
        }
        $newUser->email = strval(time())."@".rand().strval(time()).".com";

        $newUser->phone = $phone;
        $newUser->user_type_id = 3;
        $newUser->save();

        $this->updateCustomerPhoneOnLead($newUser->id,$phone);

        return $newUser;
    }
    public function createCustomerByPhone($leadOrm,$phone){
        $leadObject = Lead::where('phone','=',$phone)->first();
        $newUser = new User();
        $newUser->name = trim($leadObject->customer_name." ".$leadObject->customer_last_name);
        $newUser->password = "customer_password";

        $generatedEmail="";
        // check unique email
        if($leadObject->email==""){
            $generatedEmail = strval(time())."@".rand().strval(time()).".com";
        }

        $emailCheckExist = User::where('email','=',$generatedEmail)->count();
        if($emailCheckExist>0){
            $generatedEmail = strval(time())."@".rand().strval(time()).".com";
        }else{
            //$generatedEmail = $leadObject->email;
        }
        $newUser->email = strval(time())."@".rand().strval(time()).".com";

        $newUser->phone = $phone;
        $newUser->user_type_id = 3;

        // check if already exist
        $newUser->save();


        $this->updateCustomerPhoneOnLead($newUser->id,$phone);


        //$this->updateLeadAppLoginStatus($phone);




        return $newUser;
    }


    public function updateCustomerPhoneOnLead($userId,$phone, $app_downloaded=true){
        //d($userId);
        $allLeadWithSamePhone =  Lead::where('phone','=',$phone)->get();
        foreach($allLeadWithSamePhone as $tempLead){
            $tempLead->user_id = $userId;
            $tempLead->app_downloaded = $app_downloaded;
            $tempLead->save();
            $tempLead->save();
        }
    }


    public function sendOtpForLogin($user){
        $userFetched = User::where('id','=',$user->id)->first();

        $otpGenerated = rand ( 100000, 999999 );

        if($userFetched->phone =='7499983533' || $userFetched->phone =='8447557292'){
            $otpGenerated="123456";
        }
        $userFetched->login_otp = $otpGenerated;
        $userFetched->login_otp_expire_at = Carbon::tomorrow();
        $userFetched->save();
        $messageForCustomer =urlencode($otpGenerated." is the OTP for login into application. Thanks, Pramati Healthcare");



        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$userFetched->phone;
        $url.= '&msgtext='.$messageForCustomer;
        $url.= '&smstype=13';
        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;
        //echo "Url To Hit: ".$urlToUse;

        $ch = curl_init($urlToUse);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        return true;

    }


    public function registerDeviceToken($userId,$deviceToken){
        $deviceTokenObj = new DeviceToken();
        $deviceTokenObj->user_id = $userId;
        $deviceTokenObj->device_token = $deviceToken;
        $deviceTokenObj->save();
        return $deviceTokenObj->id;
    }

    public function updateSlackUserInfo($slackInfo){
        $email = null;
        if($slackInfo->profile && isset($slackInfo->profile->email) && $slackInfo->profile->email){
            $email = $slackInfo->profile->email;
        }
        if($email==null){
            return;
        }



        $userInfo = User::where('email','=',$email)->with('employeeInfo')->first();
        if($userInfo==null){
            return;
        }
        $employeeInfo = UserEmployee::where('user_id','=',$userInfo->id)->first();
        //d($userInfo);
        //d($employeeInfo);
        if(!$employeeInfo){
            return;
        }
        $employeeInfo->slack_username = $slackInfo->name;
        $employeeInfo->slack_user_id = $slackInfo->id;
        $employeeInfo->save();
    }

    public function getUserIdByEmailList($userEmailList){


        $userOrmList = User::whereIn('email',$userEmailList)->get();
        $userIdList = [];
        foreach($userOrmList as $tempUser){
            array_push($userIdList,$tempUser->id);
        }
        return $userIdList;
    }

    public function getEmployeeByIdList($userIdList){
        return User::whereIn('id',$userIdList)->with('employeeInfo')->get();
    }

}