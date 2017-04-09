<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Models\DTO\AilmentDTO;
use App\Models\DTO\LeadReferenceDTO;
use App\Models\Enums\SCConstants;
use Illuminate\Support\Facades\Auth;
use PhpSpec\IO\IOInterface;

class CommonRestService implements ICommonRestContract
{
    protected $operationDomainservice;

    public function __construct(IOperationDomainContract $operationDomainservice){
        $this->operationDomainservice = $operationDomainservice;
    }

    public function getLeadReferences()
    {
        $leadReferencesList = $this->operationDomainservice->getLeadReferencesList();
        $tempReturn = array();
        $leadReferenceDto = new LeadReferenceDTO();
        foreach ($leadReferencesList as $tempItem){
            array_push($tempReturn,$leadReferenceDto->convertToDTO($tempItem) );
        }
        return $tempReturn;
    }

    public function getShifts()
    {
        $shiftList = $this->operationDomainservice->getShifts();
        return $shiftList;
    }

    public function getAilments()
    {
        $AilmentList = $this->operationDomainservice->getAilments();
        $toReturnList = array();
        $ailmentDto = new AilmentDTO();
        foreach($AilmentList as $tempAil){
            array_push($toReturnList,$ailmentDto->convertToDTO($tempAil));
        }
        return $toReturnList;
    }

    public function getEquipments()
    {
        $EquipmentList = $this->operationDomainservice->getEquipments();
        return $EquipmentList;
    }

    public function getLanguages()
    {
        $LanguageList = $this->operationDomainservice->getLanguages();
        return $LanguageList;
    }

    public function getReligions()
    {
        $ReligionList = $this->operationDomainservice->getReligions();
        return $ReligionList;
    }

    public function getAgeRangesForFront(){
        $ageRangeList = $this->operationDomainservice->getAgeRanges();
        return $ageRangeList;
    }
    public function getFoodTypes(){
        $foodTypes = $this->operationDomainservice->getFoodTypes();
        return $foodTypes;
    }
    public function getServicesList($sorted)
    {
        $servicesList = $this->operationDomainservice->getServices($sorted);
        return $servicesList;
    }
    public function getConditions()
    {
        $Conditions = $this->operationDomainservice->getConditions();
        return $Conditions;
    }
    public function getComplaints()
    {
        $Complaints = $this->operationDomainservice->getComplaints();
        return $Complaints;
    }
    public function getPtconditions()
    {
        $Ptconditions = $this->operationDomainservice->getPtconditions();
        return $Ptconditions;
    }
    public function getModalities()
    {
        $Modalities = $this->operationDomainservice->getModalities();
        return $Modalities;
    }

    public function getAilmentList($serviceId) {
        $ailmentList = $this->operationDomainservice->getAilmentList();
        return $ailmentList;
    }

    public function getTaskList($ailmentId) {
        $taskList = $this->operationDomainservice->getTaskList();
        return $taskList;
    }
    
    public function getExportData() {
        $tableData = $this->operationDomainservice->getExportData();
        return $tableData;
    }
    
    public function getVendorData($id) {
        $data= $this->operationDomainservice->getVendorData($id);
        return $data;
    }
}