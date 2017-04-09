<?php

namespace App\Services\Domain;

use App\Contracts\Domain\IEmployeeDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Domain\IVendorDomainContract;
use App\Models\Article;
use App\Models\Enums\PramatiConstants;
use App\Models\Enums\SCConstants;
use App\Models\ORM\UserEmployee;
use App\Models\ORM\UserVendor;
use App\Models\ORM\VendorBankDetail;
use App\Models\ORM\VendorTask;
use App\Models\ORM\VendorTracker;
use App\Models\User;
use Carbon\Carbon;

class EmployeeDomainService implements IEmployeeDomainContract
{

    public function getAllEmployee(){
        return User::where('user_type_id','=',PramatiConstants::EMPLOYEE_USER_TYPE)
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->get();
    }

    public function getAllEmployeeTrackingData(){
        return User::whereIn('user_type_id',array(PramatiConstants::EMPLOYEE_USER_TYPE,PramatiConstants::CAREGIVER_USER_TYPE))
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->orderBy('name','asc')
            ->get();
    }

    public function getFilteredEmployeeTrackingData($userName,$limit){
        if($userName != ''){
         return User::whereIn('user_type_id',array(PramatiConstants::EMPLOYEE_USER_TYPE,PramatiConstants::CAREGIVER_USER_TYPE))
            ->where('users.name','<>','')
            ->where('name','like',$userName.'%')
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->with('employeeTracking')
            ->orderBy('name','asc')
            ->limit($limit)
            ->get();   
        }else{
         return User::whereIn('user_type_id',array(PramatiConstants::EMPLOYEE_USER_TYPE,PramatiConstants::CAREGIVER_USER_TYPE))
            ->where('users.name','<>','')
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->with('employeeTracking')                 
            ->orderBy('name','asc')
            ->limit($limit)
            ->get();   
        }
    }
    
    public function getEmployeeDetailedOrm($employeeId){
        return User::where('id','=',$employeeId)
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->first();
    }

    public function generateSlackUsernameForEmployee($employeeId){
        $userInfo = User::where('id','=',$employeeId)
            ->with('employeeInfo')
            ->with('employeeInfo.departments')
            ->first();

        $userEmployee=null;
        if($userInfo->employeeInfo==null){
            $userEmployee = new UserEmployee();
            $userEmployee->user_id = $employeeId;
            $userEmployee->save();
        }else{
            $userEmployee = UserEmployee::where('user_id','=',$employeeId)->first();
        }
        $str = preg_replace('/\s+/', '', $userInfo->name);
        $userEmployee->slack_invitation_send_at = Carbon::now();
        $userEmployee->save();
        return $str;
    }




}