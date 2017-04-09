<?php
namespace App\Models\DTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CustomerVendorStatusDTO
{


    public $id;

    public $statusDate;

    public $statusDateString;

    public $statusText;

    public $comment;

    public $dateTime;

    public function convertToDto($vendorStatus){
        $customerVendorStatusDto = new CustomerVendorStatusDTO();
        $customerVendorStatusDto->setId($vendorStatus->id);
        $customerVendorStatusDto->setStatusDate($vendorStatus->created_at);
        $carbonDate = Carbon::parse($vendorStatus->created_at);
        if($carbonDate->isToday()){
            $customerVendorStatusDto->setStatusDateString("Today (".$carbonDate->toDayDateTimeString().")");
        }else if($carbonDate->isYesterday()){
            $customerVendorStatusDto->setStatusDateString("Yesterday (".$carbonDate->toDayDateTimeString().")");
        }else{
            $customerVendorStatusDto->setStatusDateString($carbonDate->toDayDateTimeString());
        }
        if($vendorStatus->vendor_status_key=='VENDOR_NOT_REACHED'){
            $customerVendorStatusDto->setStatusText("Caregiver not reached");
        }
        $customerVendorStatusDto->setComment($vendorStatus->comment);

        $customerVendorStatusDto->setDateTime($vendorStatus->created_at);
        return $customerVendorStatusDto;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
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
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * @param mixed $statusDate
     */
    public function setStatusDate($statusDate)
    {
        $this->statusDate = $statusDate;
    }

    /**
     * @return mixed
     */
    public function getStatusDateString()
    {
        return $this->statusDateString;
    }

    /**
     * @param mixed $statusDateString
     */
    public function setStatusDateString($statusDateString)
    {
        $this->statusDateString = $statusDateString;
    }

    /**
     * @return mixed
     */
    public function getStatusText()
    {
        return $this->statusText;
    }

    /**
     * @param mixed $statusText
     */
    public function setStatusText($statusText)
    {
        $this->statusText = $statusText;
    }



}