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

class LeadGridItemDTO
{


    public $id;

    public $customerName;

    public $enquiryDate;

    public $startDate;

    public $service;

    public $saleApproved;

    public $sourceValidated;

    public $employeeAssigned;

    public $primaryVendorAssigned;

    public $backUpVendorAssigned;

    public $vendorSource;

    public $qcUserAssigned;
    public $fieldUserAssigned;

    public $operationStatus;

    public $currentStatus;

    public $clientFeedback;

    public $vendorFeedback;

    public $submissionMode;

    public $appInstalled;

    public $salesApprovedAt;

    public $employeeAssignedAt;

    /**
     * @return mixed
     */
    public function getBackUpVendorAssigned()
    {
        return $this->backUpVendorAssigned;
    }

    /**
     * @param mixed $backUpVendorAssigned
     */
    public function setBackUpVendorAssigned($backUpVendorAssigned)
    {
        $this->backUpVendorAssigned = $backUpVendorAssigned;
    }



    /**
     * @return mixed
     */
    public function getClientFeedback()
    {
        return $this->clientFeedback;
    }

    /**
     * @param mixed $clientFeedback
     */
    public function setClientFeedback($clientFeedback)
    {
        $this->clientFeedback = $clientFeedback;
    }

    /**
     * @return mixed
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param mixed $currentStatus
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;
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
    public function getEmployeeAssigned()
    {
        return $this->employeeAssigned;
    }

    /**
     * @param mixed $employeeAssigned
     */
    public function setEmployeeAssigned($employeeAssigned)
    {
        $this->employeeAssigned = $employeeAssigned;
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
    public function getFieldUserAssigned()
    {
        return $this->fieldUserAssigned;
    }

    /**
     * @param mixed $fieldUserAssigned
     */
    public function setFieldUserAssigned($fieldUserAssigned)
    {
        $this->fieldUserAssigned = $fieldUserAssigned;
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

    /**
     * @return mixed
     */
    public function getSaleApproved()
    {
        return $this->saleApproved;
    }

    /**
     * @param mixed $saleApproved
     */
    public function setSaleApproved($saleApproved)
    {
        $this->saleApproved = $saleApproved;
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
    public function getSourceValidated()
    {
        return $this->sourceValidated;
    }

    /**
     * @param mixed $sourceValidated
     */
    public function setSourceValidated($sourceValidated)
    {
        $this->sourceValidated = $sourceValidated;
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
    public function getVendorAssigned()
    {
        return $this->vendorAssigned;
    }

    /**
     * @param mixed $vendorAssigned
     */
    public function setVendorAssigned($vendorAssigned)
    {
        $this->vendorAssigned = $vendorAssigned;
    }

    /**
     * @return mixed
     */
    public function getVendorFeedback()
    {
        return $this->vendorFeedback;
    }

    /**
     * @param mixed $vendorFeedback
     */
    public function setVendorFeedback($vendorFeedback)
    {
        $this->vendorFeedback = $vendorFeedback;
    }

    /**
     * @return mixed
     */
    public function getVendorSource()
    {
        return $this->vendorSource;
    }

    /**
     * @param mixed $vendorSource
     */
    public function setVendorSource($vendorSource)
    {
        $this->vendorSource = $vendorSource;
    }

    /**
     * @return mixed
     */
    public function getQcUserAssigned()
    {
        return $this->qcUserAssigned;
    }

    /**
     * @param mixed $qcUserAssigned
     */
    public function setQcUserAssigned($qcUserAssigned)
    {
        $this->qcUserAssigned = $qcUserAssigned;
    }

    /**
     * @return mixed
     */
    public function getSubmissionMode()
    {
        return $this->submissionMode;
    }

    /**
     * @param mixed $submissionMode
     */
    public function setSubmissionMode($submissionMode)
    {
        $this->submissionMode = $submissionMode;
    }

    /**
     * @return mixed
     */
    public function getPrimaryVendorAssigned()
    {
        return $this->primaryVendorAssigned;
    }

    /**
     * @param mixed $primaryVendorAssigned
     */
    public function setPrimaryVendorAssigned($primaryVendorAssigned)
    {
        $this->primaryVendorAssigned = $primaryVendorAssigned;
    }








    public function convertToDto($leadOrm){
        $leadGridItemDto = new LeadGridItemDTO();
        $leadGridItemDto->setId($leadOrm->id);
        $leadGridItemDto->setCustomerName(ucfirst($leadOrm->customer_name." ".$leadOrm->customer_last_name));
        if($leadOrm->enquiry){
            $leadGridItemDto->setEnquiryDate($leadOrm->enquiry->created_at);
        }else{
            $leadGridItemDto->setEnquiryDate($leadOrm->created_at);
        }
        $leadGridItemDto->setStartDate($leadOrm->start_date);
        $serviceMinimalDto = new ServiceMinimalDTO();
        $leadGridItemDto->setService($serviceMinimalDto->convertToDto($leadOrm->service));

        //TODO: need to change
        $leadGridItemDto->setSaleApproved(false);
        $leadGridItemDto->setSourceValidated(false);

        // TODO END
        if($leadOrm->id ==228){
            //d($leadOrm->vendorsAssigned->last());
            //d($leadOrm->primaryVendorsAssigned->last());
        }



        if($leadOrm->employeesAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadGridItemDto->setEmployeeAssigned($userMinimalDto->convertToDto($leadOrm->employeesAssigned->last()));
           // d($leadOrm->employeesAssigned->last()->pivot->created_at);
            $leadGridItemDto->setEmployeeAssignedAt($leadOrm->employeesAssigned->last()->pivot->created_at);
        }
        if($leadOrm->primaryVendorsAssigned->count()>0){

            $lastVendor = $leadOrm->primaryVendorsAssigned->last();

            //check if app installed
            $appDB = \DB::select( \DB::raw("SELECT COUNT(*) AS appTimes FROM oauth_sessions WHERE owner_id = :owner_id AND client_id = :client_id"), array('owner_id' => $lastVendor->id, 'client_id'=>'androidapp' ));
            if($appDB[0]->appTimes > 0)
                $lastVendor->appInstalled = true;
            else
                $lastVendor->appInstalled = false;

            $userMinimalDto = new UserInfoDTO();
            $leadGridItemDto->setPrimaryVendorAssigned($userMinimalDto->convertToDtoWithApp($lastVendor));
        }
        if($leadOrm->vendorsAssigned->count()>0){

            $lastVendor = $leadOrm->vendorsAssigned->last();

            //check if app installed
            $appDB = \DB::select( \DB::raw("SELECT COUNT(*) AS appTimes FROM oauth_sessions WHERE owner_id = :owner_id AND client_id = :client_id"), array('owner_id' => $lastVendor->id, 'client_id'=>'androidapp' ));
            if($appDB[0]->appTimes > 0)
                $lastVendor->appInstalled = true;
            else
                $lastVendor->appInstalled = false;

            $userMinimalDto = new UserInfoDTO();
            $leadGridItemDto->setBackUpVendorAssigned($userMinimalDto->convertToDtoWithApp($lastVendor));
        }
        if($leadOrm->qcAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadGridItemDto->setQcUserAssigned($userMinimalDto->convertToDto($leadOrm->qcAssigned->last()));
        }
        if($leadOrm->fieldAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadGridItemDto->setFieldUserAssigned($userMinimalDto->convertToDto($leadOrm->fieldAssigned->last()));
        }

        if($leadOrm->approvalStatus->count()>0){
            $leadApprovedStatus = $leadOrm->approvalStatus->first();
            $leadGridItemDto->setSalesApprovedAt($leadApprovedStatus->created_at);
        }

        if($leadOrm->statuses->count()>0){
            $operationalStatusDto = new OperationalStatusDTO();
            $leadGridItemDto->setOperationStatus($operationalStatusDto->convertToDto($leadOrm->statuses->last()));
        }else{
            $operationalStatusDto = new OperationalStatusDTO();
            $operationalStatusDto->setLabel('Pending');
            $operationalStatusDto->setSlug('pending');
            $leadGridItemDto->setOperationStatus($operationalStatusDto);
        }

        if($leadOrm->submission_mode==PramatiConstants::LEAD_SUBMISSION_MODE_ONLINE_CALL){
            $leadGridItemDto->setSubmissionMode("Call Me Now");
        }else if($leadOrm->submission_mode==PramatiConstants::LEAD_SUBMISSION_MODE_ONLINE_FORM){
            $leadGridItemDto->setSubmissionMode("Online Form");
        }else if($leadOrm->submission_mode==PramatiConstants::LEAD_SUBMISSION_MODE_CRM){
            $leadGridItemDto->setSubmissionMode("CRM");
        }else if($leadOrm->submission_mode == PramatiConstants::LEAD_SUBMISSION_MODE_CAMPAIGN){
            $leadGridItemDto->setSubmissionMode('Campaign');
        }else{
            $leadGridItemDto->setSubmissionMode('');
        }

        $leadGridItemDto->setAppInstalled($leadOrm->app_downloaded==1?true:false);



        return $leadGridItemDto;
    }

    /**
     * @return mixed
     */
    public function getAppInstalled()
    {
        return $this->appInstalled;
    }

    /**
     * @param mixed $appInstalled
     */
    public function setAppInstalled($appInstalled)
    {
        $this->appInstalled = $appInstalled;
    }

    /**
     * @return mixed
     */
    public function getSalesApprovedAt()
    {
        return $this->salesApprovedAt;
    }

    /**
     * @param mixed $salesApprovedAt
     */
    public function setSalesApprovedAt($salesApprovedAt)
    {
        $this->salesApprovedAt = $salesApprovedAt;
    }

    /**
     * @return mixed
     */
    public function getEmployeeAssignedAt()
    {
        return $this->employeeAssignedAt;
    }

    /**
     * @param mixed $employeeAssignedAt
     */
    public function setEmployeeAssignedAt($employeeAssignedAt)
    {
        $this->employeeAssignedAt = $employeeAssignedAt;
    }







}