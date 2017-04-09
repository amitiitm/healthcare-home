<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IEmployeeDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Domain\IVendorDomainContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Contracts\Rest\IEmployeeRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Models\DTO\Employee\EmployeeDetailedDTO;
use App\Models\DTO\EmployeeDetailDTO;
use App\Models\DTO\EmployeeGridItemDTO;
use App\Models\DTO\EmployeeTrackingItemDTO;
use App\Models\DTO\UserGridItemDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\DTO\VendorDetailedDTO;
use App\Models\DTO\VendorGidItemDTO;
use App\Models\DTO\VendorGridItemDTO;
use App\Models\Enums\SCConstants;
use App\Models\ORM\UserVendor;
use App\Models\User;
use App\Templates\PRResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeRestService implements IEmployeeRestContract
{

    protected $employeeDomainservice;

    protected $slackHelperService;

    public function __construct(IEmployeeDomainContract $employeeDomainContract, ISlackHelperContract $ISlackHelperContract){
        $this->employeeDomainservice = $employeeDomainContract;
        $this->slackHelperService = $ISlackHelperContract;
    }


    public function getEmployeeGridList(){
        $employeeList = $this->employeeDomainservice->getAllEmployee();
        $employeeListDto = new EmployeeGridItemDTO();
        $toReturnList = array();
        foreach($employeeList as $tempEmployeeDto){
            array_push($toReturnList,$employeeListDto->convertToDTO($tempEmployeeDto));
        }
        return $toReturnList;

    }

    public function getEmployeeTrackingList(){
        $employeeList = $this->employeeDomainservice->getAllEmployeeTrackingData();
        $employeeListDto = new EmployeeTrackingItemDTO();
        $toReturnList = array();
        foreach($employeeList as $tempEmployeeDto){
            array_push($toReturnList,$employeeListDto->convertToDTO($tempEmployeeDto));
        }
        usort($toReturnList,function($a,$b){
            if(count($a->locations) >0 || count($b->locations)>0){
                return (count($a->locations)<count($b->locations));
            }else{
                return $a->name>$b->name;
            }
        });
        return $toReturnList;
    }
    
    public function getFilteredEmployeeTrackingList($userName,$limit){
        $employeeList = $this->employeeDomainservice->getFilteredEmployeeTrackingData($userName,$limit);
        $employeeListDto = new EmployeeTrackingItemDTO();
        $toReturnList = array();
        foreach($employeeList as $tempEmployeeDto){
            array_push($toReturnList,$employeeListDto->convertToDTONew($tempEmployeeDto));
        }
        usort($toReturnList,function($a,$b){
            if(count($a->locations) >0 || count($b->locations)>0){
                return (count($a->locations)<count($b->locations));
            }else{
                return $a->name>$b->name;
            }
        });
        return $toReturnList;
    }

    public function getEmployeeDetail($employeeId){
        $employeeDetailedOrm = $this->employeeDomainservice->getEmployeeDetailedOrm($employeeId);

        $employeeDetailedDto = new EmployeeDetailDTO();

        return $employeeDetailedDto->convertToDto($employeeDetailedOrm);
    }

    public function getEmployeeDetailedInfo($employeeId){

        $employeeDetailedOrm = $this->employeeDomainservice->getEmployeeDetailedOrm($employeeId);

        $employeeDetailedDto = new EmployeeDetailedDTO();

        $detailedDto = $employeeDetailedDto->convertToDto($employeeDetailedOrm);
        return ($detailedDto);

    }

    public function generateSlackUser($employeeId){

        $employeeDetailedOrm = $this->employeeDomainservice->getEmployeeDetailedOrm($employeeId);

        $employeeInfo = $employeeDetailedOrm->employeeInfo;
        if($employeeInfo && $employeeInfo->slack_username && $employeeInfo->slack_username!=""){
            //return PRResponse::getWarningResponse("Slack account already created.", (object)array());
        }else if(!$employeeInfo){
            // TODO: Invalid employee
        }

        // generating username
        $this->employeeDomainservice->generateSlackUsernameForEmployee($employeeId);

        $responseObject = $this->slackHelperService->generateSlackForUser($employeeDetailedOrm);

        if($responseObject){
            return PRResponse::getSuccessResponse("Slack invitation send successfully.",(object)array() );
        }
        return PRResponse::getErrorResponse("Unbale to create slack account.",(object)array());



    }



}