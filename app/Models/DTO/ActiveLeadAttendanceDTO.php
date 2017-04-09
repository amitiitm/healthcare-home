<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class ActiveLeadAttendanceDTO{


    public $id;

    public $leadId;

    public $date;

    public $customerVendorAttendance;

    public $vendorAttendance;

    public $vendorIncentive;

    public $customerName;

    public function convertToDTO($orm){


        $activeLeadAttendanceDto = new ActiveLeadAttendanceDTO();

        $activeLeadAttendanceDto->setDate($orm->active_date);

        $customerVendorAttendanceDto = new CustomerVendorAttendanceDTO();
        if($orm->customerVendorAttendance){
            //$activeLeadAttendanceDto->setCustomerVendorAttendance($customerVendorAttendanceDto->convertToDto($orm->customerVendorAttendance));
        }


        $leadVendorAttendanceDto = new LeadVendorAttendanceDTO();
        //$activeLeadAttendanceDto->setVendorAttendance($leadVendorAttendanceDto->convertToDTO($orm->vendorAttendance));

        $activeLeadAttendanceDto->setLeadId($orm->lead_id);

        if($orm->lead){
            $activeLeadAttendanceDto->setCustomerName(trim($orm->lead->customer_name." ".$orm->lead->customer_last_name));
        }



        return $activeLeadAttendanceDto;

    }

    public function convertToDTODetailed($orm){


        $activeLeadAttendanceDto = new ActiveLeadAttendanceDTO();

        $activeLeadAttendanceDto->setDate($orm->active_date);

        $customerVendorAttendanceDto = new CustomerVendorAttendanceDTO();
        if($orm->customerVendorAttendance){
            //$activeLeadAttendanceDto->setCustomerVendorAttendance($customerVendorAttendanceDto->convertToDto($orm->customerVendorAttendance));
        }


        $leadVendorAttendanceDto = new LeadVendorAttendanceDTO();
        //$activeLeadAttendanceDto->setVendorAttendance($leadVendorAttendanceDto->convertToDTO($orm->vendorAttendance));

        $activeLeadAttendanceDto->setLeadId($orm->lead_id);

        if($orm->lead){
            $activeLeadAttendanceDto->setCustomerName(trim($orm->lead->customer_name." ".$orm->lead->customer_last_name));

            //set vendor
            $activeLeadAttendanceDto->setVendorAttendance($orm->vendorAttendance);
        }



        return $activeLeadAttendanceDto;

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
    public function getCustomerVendorAttendance()
    {
        return $this->customerVendorAttendance;
    }

    /**
     * @param mixed $customerVendorAttendance
     */
    public function setCustomerVendorAttendance($customerVendorAttendance)
    {
        $this->customerVendorAttendance = $customerVendorAttendance;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * @param mixed $leadId
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
    }



    /**
     * @return mixed
     */
    public function getVendorAttendance()
    {
        return $this->vendorAttendance;
    }

    /**
     * @param mixed $vendorAttendance
     */
    public function setVendorAttendance($vendorAttendance)
    {
        $this->vendorAttendance = $vendorAttendance;
    }

    /**
     * @param mixed $vendorIncentive
     */
    public function setVendorIncentive($vendorIncentive)
    {
        $this->vendorIncentive = $vendorIncentive;
    }




}