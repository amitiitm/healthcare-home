<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IEmployeeRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Gender;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class EmployeeRestController extends Controller
{

    protected $commonRestServices;

    protected $employeeRestServices;

    public function __construct( IEmployeeRestContract $employeeRestContract, ICommonRestContract $commonRestContract)
    {
        $this->employeeRestServices = $employeeRestContract;
        $this->commonRestServices = $commonRestContract;
    }

    public function getEmployeeGridData(){
        return Response::json(PRResponse::getSuccessResponse('',$this->employeeRestServices->getEmployeeGridList()));
    }

    public function getEmployeeTrackingData(){
        $listType = Input::get('list_type');
        $limit = Input::get('limit');
        $userName = Input::get('user_name');
        if($listType !== '' && $listType == 'filtered'){
          return Response::json(PRResponse::getSuccessResponse('',$this->employeeRestServices->getFilteredEmployeeTrackingList($userName,$limit)));  
        }else{
         return Response::json(PRResponse::getSuccessResponse('',$this->employeeRestServices->getEmployeeTrackingList()));   
        }      
    }

    public function getEmployeeDetail($employeeId){
        return Response::json(PRResponse::getSuccessResponse('',$this->employeeRestServices->getEmployeeDetail($employeeId)));
    }

    public function getEmployeeDetailedInfo($employeeId){
        return Response::json(PRResponse::getSuccessResponse('',$this->employeeRestServices->getEmployeeDetailedInfo($employeeId)));
    }

    public function generateSlackUser($employeeId){
        return Response::json($this->employeeRestServices->generateSlackUser($employeeId));
    }



}