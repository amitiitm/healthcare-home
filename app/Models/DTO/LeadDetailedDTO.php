<?php
namespace App\Models\DTO;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class LeadDetailedDTO
{


    public $id;

    public $customerName;

    public $customerLastName;

    public $enquiryDate;

    public $startDate;

    public $UserCreated;

    public $service;

    public $saleApproved;

    public $sourceValidated;

    public $employeeAssigned;

    public $vendorAssigned;

    public $primaryVendorAssigned;

    public $qcAssignmentId;

    public $fieldAssigned;

    public $vendorSource;

    public $fieldUserAssigned;

    public $operationStatus;

    public $currentStatus;

    public $clientFeedback;

    public $vendorFeedback;

    public $patient;

    public $logs;

    public $address;

    public $locality;

    public $email;

    public $enquiry;

    public $leadReference;

    public $leadSource;

    public $remark;

    public $paymentType;

    public $paymentPeriod;

    public $paymentMode;

    public $price;

    public $priceUnit;

    public $landmark;

    public $phone;

    public $statusLog;

    public $taskOther;

    public $slackChannelId;
    public $slackChannelName;


    public $customerUsingSmartPhone;

    public $appInstalled;



    public function convertToDto($leadOrm){

        //dd($leadOrm->prices->last());


        $leadDetailedDTO = new LeadDetailedDTO();
        $leadDetailedDTO->setId($leadOrm->id);
        $leadDetailedDTO->setCustomerName($leadOrm->customer_name);
        $leadDetailedDTO->setCustomerLastName($leadOrm->customer_last_name);
        $leadDetailedDTO->setUserCreated($leadOrm->userCreated);
        $leadDetailedDTO->setTaskOther($leadOrm->task_other);
        $leadDetailedDTO->setPriceUnit($leadOrm->price_unit_id);

        if($leadOrm->enquiry){
            $leadDetailedDTO->setEnquiryDate($leadOrm->enquiry->created_at);
        }else{
            $leadDetailedDTO->setEnquiryDate($leadOrm->created_at);
        }
        $serviceMinimalDto = new ServiceMinimalDTO();
        $leadDetailedDTO->setService($serviceMinimalDto->convertToDto($leadOrm->service));

        //TODO: need to change
        $leadDetailedDTO->setSaleApproved(false);
        $leadDetailedDTO->setSourceValidated(false);

        // TODO END
        $patientInfo = new PatientDTO();
        //d($leadOrm->patient);
        $leadDetailedDTO->setPatient($patientInfo->convertToDTO($leadOrm->patient));

        $leadDetailedDTO->setLogs(array());

        if($leadOrm->employeesAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadDetailedDTO->setEmployeeAssigned($userMinimalDto->convertToDto($leadOrm->employeesAssigned->last()));
        }

        if($leadOrm->primaryVendorsAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadDetailedDTO->setPrimaryVendorAssigned($userMinimalDto->convertToDto($leadOrm->primaryVendorsAssigned->last()));
        }
        if($leadOrm->vendorsAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadDetailedDTO->setVendorAssigned($userMinimalDto->convertToDto($leadOrm->vendorsAssigned->last()));
        }
        if($leadOrm->qcAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadDetailedDTO->setQcAssigned($userMinimalDto->convertToDto($leadOrm->qcAssigned->last()));
            $leadDetailedDTO->setQcAssignmentId($leadOrm->qcAssigned->last()->id);
        }


        if($leadOrm->fieldAssigned->count()>0){
            $userMinimalDto = new UserInfoDTO();
            $leadDetailedDTO->setFieldAssigned($userMinimalDto->convertToDto($leadOrm->fieldAssigned->last()));
        }
        if($leadOrm->statuses->count()>0){
            $operationalStatusDto = new OperationalStatusDTO();
            $leadDetailedDTO->setOperationStatus($operationalStatusDto->convertToDTO($leadOrm->statuses->last()));
        }

        $leadDetailedDTO->setAddress($leadOrm->address);
        if($leadOrm->locality){
            $localityDto = new LocalityDTO();
            $leadDetailedDTO->setLocality($localityDto->convertToDto($leadOrm->locality));
        }
        $leadDetailedDTO->setEmail($leadOrm->email);
        if($leadOrm->enquiry){
            $leadEnquiryDto = new EnquiryMinimalDTO();
            $leadDetailedDTO->setEnquiry($leadEnquiryDto->convertToDto($leadOrm->enquiry));
        }
        //d($leadOrm->leadReference);
        if($leadOrm->leadReference){
            $leadReferenceDto = new LeadReferenceDTO();
            $leadDetailedDTO->setLeadReference($leadReferenceDto->convertToDTO($leadOrm->leadReference));
        }
        //d($leadOrm->leadSource);
        if($leadOrm->leadSource){
            $leadSourceDto = new LeadSourceDTO();
            $leadDetailedDTO->setLeadSource($leadSourceDto->convertToDTO($leadOrm->leadSource));
        }

        $leadDetailedDTO->setStartDate($leadOrm->start_date);

        $leadDetailedDTO->setRemark($leadOrm->remark);

        if($leadOrm->paymentType){
            $paymentTypeDto = new PaymentTypeDTO();
            $leadDetailedDTO->setPaymentType($paymentTypeDto->convertToDTO($leadOrm->paymentType));
        }
        if($leadOrm->paymentMode){
            $paymentModeDto = new PaymentModeDTO();
            $leadDetailedDTO->setPaymentMode($paymentModeDto->convertToDTO($leadOrm->paymentMode));
        }
        if($leadOrm->paymentPeriod){
            $paymentPeriodDto = new PaymentPeriodDTO();
            $leadDetailedDTO->setPaymentPeriod($paymentPeriodDto->convertToDTO($leadOrm->paymentPeriod));
        }
        $operationalStatusDto = new OperationalStatusDTO();
        if($leadOrm->statuses->count()>0){
            $statusDto = new OperationalStatusDTO();
            $tempListArr = array();
            foreach($leadOrm->statuses as $tempStatus){
                array_push($tempListArr,$statusDto->convertToDTO($tempStatus));
            }
            $leadDetailedDTO->setStatusLog($tempListArr);
            $leadDetailedDTO->setCurrentStatus($operationalStatusDto->convertToDto($leadOrm->statuses->last()));
        }else{
            $operationalStatusDto->setLabel('Pending');
            $leadDetailedDTO->setOperationStatus($operationalStatusDto);
        }
        $leadDetailedDTO->setLandMark($leadOrm->landmark);
        $leadDetailedDTO->setPhone($leadOrm->phone);
        if($leadOrm->prices->last()){
            $leadDetailedDTO->price=$leadOrm->prices->last()->price;
            $leadDetailedDTO->priceUnit=$leadOrm->prices->last()->priceUnit;
        }
        $leadDetailedDTO->setSlackChannelId('');

        $leadDetailedDTO->setSlackChannelName($leadOrm->slack_channel_name);



        $leadDetailedDTO->setAppInstalled($leadOrm->app_downloaded==1?true:false);




        if($leadOrm->slack_channel_id != "" && $leadOrm->slack_channel_id !='1'){
            $leadDetailedDTO->setSlackChannelId($leadOrm->slack_channel_id);
        }

        return $leadDetailedDTO;
    }

    /**
     * @return mixed
     */
    public function getUserCreated()
    {
        return $this->UserCreated;
    }

    public function setUserCreated($UserCreated)
    {
        $this->UserCreated = $UserCreated;
    }

    public function getStatusLog()
    {
        return $this->statusLog;
    }

    /**
     * @param mixed $statusLog
     */
    public function setStatusLog($statusLog)
    {
        $this->statusLog = $statusLog;
    }



    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEnquiry()
    {
        return $this->enquiry;
    }

    /**
     * @param mixed $enquiry
     */
    public function setEnquiry($enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * @return mixed
     */
    public function getLeadReference()
    {
        return $this->leadReference;
    }

    /**
     * @param mixed $leadReference
     */
    public function setLeadReference($leadReference)
    {
        $this->leadReference = $leadReference;
    }

    /**
     * @return mixed
     */
    public function getLeadSource()
    {
        return $this->leadSource;
    }

    /**
     * @param mixed $leadSource
     */
    public function setLeadSource($leadSource)
    {
        $this->leadSource = $leadSource;
    }

    /**
     * @return mixed
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param mixed $locality
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
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
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param mixed $patient
     */
    public function setPatient($patient)
    {
        $this->patient = $patient;
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
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return mixed
     */
    public function getPaymentMode()
    {
        return $this->paymentMode;
    }

    /**
     * @param mixed $paymentMode
     */
    public function setPaymentMode($paymentMode)
    {
        $this->paymentMode = $paymentMode;
    }

    /**
     * @return mixed
     */
    public function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }

    /**
     * @param mixed $paymentPeriod
     */
    public function setPaymentPeriod($paymentPeriod)
    {
        $this->paymentPeriod = $paymentPeriod;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPriceUnit()
    {
        return $this->priceUnit;
    }

    public function setPriceUnit($priceUnit)
    {
        $this->priceUnit = $priceUnit;
    }

    /**
     * @return mixed
     */
    public function getLandMark()
    {
        return $this->landmark;
    }

    /**
     * @param mixed $landMark
     */
    public function setLandMark($landMark)
    {
        $this->landmark = $landMark;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }

    /**
     * @param mixed $customerLastName
     */
    public function setCustomerLastName($customerLastName)
    {
        $this->customerLastName = $customerLastName;
    }

    public function getTaskOther()
    {
        return $this->taskOther;
    }
    
    public function setTaskOther($taskOther)
    {
        $this->taskOther = $taskOther;
    }

    /**
     * @return mixed
     */
    public function getQcAssigned()
    {
        return $this->qcAssigned;
    }

    /**
     * @param mixed $qcAssigned
     */
    public function setQcAssigned($qcAssigned)
    {
        $this->qcAssigned = $qcAssigned;
    }

    /**
     * @return mixed
     */
    public function getFieldAssigned()
    {
        return $this->fieldAssigned;
    }

    /**
     * @param mixed $fieldAssigned
     */
    public function setFieldAssigned($fieldAssigned)
    {
        $this->fieldAssigned = $fieldAssigned;
    }

    /**
     * @return mixed
     */
    public function getQcAssignmentId()
    {
        return $this->qcAssignmentId;
    }

    /**
     * @param mixed $qcAssignmentId
     */
    public function setQcAssignmentId($qcAssignmentId)
    {
        $this->qcAssignmentId = $qcAssignmentId;
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



    









    /**
     * @return mixed
     */
    public function getSlackChannelId()
    {
        return $this->slackChannelId;
    }

    /**
     * @param mixed $slackChannelId
     */
    public function setSlackChannelId($slackChannelId)
    {
        $this->slackChannelId = $slackChannelId;
    }

    /**
     * @return mixed
     */
    public function getSlackChannelName()
    {
        return $this->slackChannelName;
    }

    /**
     * @param mixed $slackChannelName
     */
    public function setSlackChannelName($slackChannelName)
    {
        $this->slackChannelName = $slackChannelName;
    }

    /**
     * @return mixed
     */
    public function getCustomerUsingSmartPhone()
    {
        return $this->customerUsingSmartPhone;
    }

    /**
     * @param mixed $customerUsingSmartPhone
     */
    public function setCustomerUsingSmartPhone($customerUsingSmartPhone)
    {
        $this->customerUsingSmartPhone = $customerUsingSmartPhone;
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









}