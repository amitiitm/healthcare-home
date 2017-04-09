<?php
namespace App\Http\Composers;


use App\Models\DTO\AuthObject;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class NavigationComposer
{


    public function composeUserView(View $view){
        $loggedUser = Auth::user();

        $authObject = new AuthObject();
        if(Auth::user()){
            $authObject->setName($loggedUser->name);
            $authObject->setEmail($loggedUser->email);
            $authObject->setId($loggedUser->id);
            $authObject->setUserTypeId($loggedUser->user_type_id);
            $authObject->setIsAdmin($loggedUser->is_admin);
            $trackingAuthorized = array('nandkishore@pramaticare.com','mohit2007gupta@gmail.com','meenu@pramaticare.com');
            $authObject->setIsAuthorizedForTracking(in_array($loggedUser->email,$trackingAuthorized));

            $attendanceAuthorized = array('sachin@pramaticare.com','jyoti@pramaticare.com');

            $authObject->setIsAuthorizedForAttendanceReport(in_array($loggedUser->email,$attendanceAuthorized));

            $authObject->setImageUrl(url("/user/profile/".$loggedUser->id."?size=small"));
        }

        $view->with('authObject', $authObject);
    }
    public function composeAdminNavigation(View $view)
    {
        $loggedUser = Auth::user();

        $userInfo = array(
            'name'  =>  $loggedUser->getFirstName(),
            'memberSince'  =>  Carbon::parse($loggedUser->getCreatedAt(), ORConstants::TIME_ZONE_INDIA)->toFormattedDateString()
        );

        $view->with('userInfo', $userInfo)->with('isAdmin',true);
    }

    public function composeCustomerNavigation(View $view)
    {
        $loggedUser = Auth::user();

        $userInfo = array(
            'name'  =>  $loggedUser->getFirstName(),
            'memberSince'  =>  Carbon::parse($loggedUser->getCreatedAt(), ORConstants::TIME_ZONE_INDIA)->toFormattedDateString()
        );

        $view->with('userInfo', $userInfo);
        $view->with('userInfo', $userInfo)->with('isAdmin',false);
    }

    public function composeVendorNavigation(View $view)
    {
        $loggedUser = Auth::user();

        $userInfo = array(
            'name'  =>  $loggedUser->getFirstName(),
            'member_since'  =>  Carbon::parse($loggedUser->getCreatedAt(), ORConstants::TIME_ZONE_INDIA)->toFormattedDateString()
        );

        $view->with('userInfo', $userInfo);
        $view->with('userInfo', $userInfo)->with('isAdmin',false);
    }


}