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

class VendorAvailabilityDTO{

    public $id;

    public $vendorId;

    public $available;

    public $availabilityOption;

    public $availabilityReason;

    public $availableShift;

    public $availableZone;

    public $availableDate;

    public $availableOtherReason;




    public function convertToDTO($vendorAvailabilityOrm){
        $vendorAvailabilityDto = new VendorAvailabilityDTO();
        $vendorAvailabilityDto->setId($vendorAvailabilityOrm->id);
        $vendorAvailabilityDto->setVendorId($vendorAvailabilityOrm->vendor_id);
        $vendorAvailabilityDto->setAvailable($vendorAvailabilityOrm->available);
        $vendorAvailabilityDto->setAvailableShift($vendorAvailabilityOrm->availabilityShift);
        $vendorAvailabilityDto->setAvailableZone($vendorAvailabilityOrm->availabilityZone);
        $vendorAvailabilityDto->setAvailabilityOption($vendorAvailabilityOrm->availabilityOption);
        $vendorAvailabilityDto->setAvailabilityReason($vendorAvailabilityOrm->availabilityReason);
        $vendorAvailabilityDto->setAvailableOtherReason($vendorAvailabilityOrm->other_reason);
        $vendorAvailabilityDto->setAvailableDate($vendorAvailabilityOrm->available_date);
        return $vendorAvailabilityDto;
    }

    /**
     * @return mixed
     */
    public function getAvailabilityOption()
    {
        return $this->availabilityOption;
    }

    /**
     * @param mixed $availabilityOption
     */
    public function setAvailabilityOption($availabilityOption)
    {
        $this->availabilityOption = $availabilityOption;
    }

    /**
     * @return mixed
     */
    public function getAvailabilityReason()
    {
        return $this->availabilityReason;
    }

    /**
     * @param mixed $availabilityReason
     */
    public function setAvailabilityReason($availabilityReason)
    {
        $this->availabilityReason = $availabilityReason;
    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }

    /**
     * @return mixed
     */
    public function getAvailableDate()
    {
        return $this->availableDate;
    }

    /**
     * @param mixed $availableDate
     */
    public function setAvailableDate($availableDate)
    {
        $this->availableDate = $availableDate;
    }

    /**
     * @return mixed
     */
    public function getAvailableOtherReason()
    {
        return $this->availableOtherReason;
    }

    /**
     * @param mixed $availableOtherReason
     */
    public function setAvailableOtherReason($availableOtherReason)
    {
        $this->availableOtherReason = $availableOtherReason;
    }

    /**
     * @return mixed
     */
    public function getAvailableShift()
    {
        return $this->availableShift;
    }

    /**
     * @param mixed $availableShift
     */
    public function setAvailableShift($availableShift)
    {
        $this->availableShift = $availableShift;
    }

    /**
     * @return mixed
     */
    public function getAvailableZone()
    {
        return $this->availableZone;
    }

    /**
     * @param mixed $availableZone
     */
    public function setAvailableZone($availableZone)
    {
        $this->availableZone = $availableZone;
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
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * @param mixed $vendorId
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
    }





}