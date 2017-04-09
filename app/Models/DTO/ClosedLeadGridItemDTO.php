<?php
namespace App\Models\DTO;
use App\Models\Enums\PramatiConstants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class ClosedLeadGridItemDTO
{


    public $id;

    public $customerName;

    public $enquiryDate;

    public $startDate;

    public $service;

    public $operationStatus;

    public $closedDate;

    public $markedBy;

    public $reason;

    public $status;



    public function convertToDto($leadOrm){

        $leadGridItemDto = new ClosedLeadGridItemDTO();
        if(!isset($leadOrm) || !isset($leadOrm->lead) || !$leadOrm->lead){
            return $leadGridItemDto;
        }
        $leadGridItemDto->setId($leadOrm->lead->id);
        $leadGridItemDto->setCustomerName(ucfirst($leadOrm->lead->customer_name." ".$leadOrm->lead->customer_last_name));
        if($leadOrm->enquiry){
            $leadGridItemDto->setEnquiryDate($leadOrm->lead->enquiry->created_at);
        }else{
            $leadGridItemDto->setEnquiryDate($leadOrm->lead->created_at);
        }
        $leadGridItemDto->setStartDate($leadOrm->lead->start_date);
        $serviceMinimalDto = new ServiceMinimalDTO();
        $leadGridItemDto->setService($serviceMinimalDto->convertToDto($leadOrm->lead->service));


        if($leadOrm->status_date){
            $leadGridItemDto->setClosedDate($leadOrm->status_date);
        }else{
            $leadGridItemDto->setClosedDate($leadOrm->created_at);
        }


        $userMinimalDTO = new UserMinimalDTO();
        $leadGridItemDto->setMarkedBy($userMinimalDTO->convertToDto($leadOrm->user));

        $reasonDto = new CommonDTO();
        $leadGridItemDto->setReason($reasonDto->convertToDTO($leadOrm->reason));

        if($leadOrm->lead->statuses->count()>0){
            $operationalStatusDto = new OperationalStatusMinimalDTO();
            $leadGridItemDto->setOperationStatus($operationalStatusDto->convertToDto($leadOrm->lead->statuses->last()));
        }else{
            $operationalStatusDto = new OperationalStatusDTO();
            $operationalStatusDto->setLabel('Pending');
            $operationalStatusDto->setSlug('pending');
            $leadGridItemDto->setOperationStatus($operationalStatusDto);
        }

        return $leadGridItemDto;
    }

    /**
     * @return mixed
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * @param mixed $closedDate
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param mixed $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return mixed
     */
    public function getEnquiryDate()
    {
        return $this->enquiryDate;
    }

    /**
     * @param mixed $enquiryDate
     */
    public function setEnquiryDate($enquiryDate)
    {
        $this->enquiryDate = $enquiryDate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMarkedBy()
    {
        return $this->markedBy;
    }

    /**
     * @param mixed $markedBy
     */
    public function setMarkedBy($markedBy)
    {
        $this->markedBy = $markedBy;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getOperationStatus()
    {
        return $this->operationStatus;
    }

    /**
     * @param mixed $operationStatus
     */
    public function setOperationStatus($operationStatus)
    {
        $this->operationStatus = $operationStatus;
    }





}