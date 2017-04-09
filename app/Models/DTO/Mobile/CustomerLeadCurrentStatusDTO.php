<?php
namespace App\Models\DTO\Mobile;
use App\Models\DTO\OperationalStatusDTO;
use App\Models\DTO\ServiceMinimalDTO;
use App\Models\DTO\UserInfoDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\ORM\Ailment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CustomerLeadCurrentStatusDTO{


    public $patientName;

    public $address;

    public $status;

    public $startDate;

    public $shift;

    public $careGiverAssigned;


    public function convertToDTO($leadOrm){
        $customerLeadCurrentStatusDto = new CustomerLeadCurrentStatusDTO();
        $operationalStatusDto = new OperationalStatusDTO();
        $currentStatus = $operationalStatusDto->convertToDto($leadOrm->statuses->last());
        $customerLeadCurrentStatusDto->setStatus($currentStatus->label);
        $carbonDate = Carbon::parse($leadOrm->start_date);
        $customerLeadCurrentStatusDto->setStartDate($carbonDate->format('d/m/Y'));
        if($leadOrm->patient && $leadOrm->patient->shift){
            $customerLeadCurrentStatusDto->setShift($leadOrm->patient->shift->label);
            $customerLeadCurrentStatusDto->setPatientName($leadOrm->patient->name);
        }

        if($leadOrm->locality){
            $customerLeadCurrentStatusDto->setAddress(trim($leadOrm->address." ".$leadOrm->locality->formatted_address));
        }

        if($leadOrm->vendorsAssigned->count()>0){
            $userMinimalDto = new UserMinimalDTO();
            $userMinimalDto = new UserInfoDTO();
            $customerLeadCurrentStatusDto->setCareGiverAssigned($userMinimalDto->convertToDto($leadOrm->vendorsAssigned->last()));
        }
        return $customerLeadCurrentStatusDto;
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
    public function getPatientName()
    {
        return $this->patientName;
    }

    /**
     * @param mixed $patientName
     */
    public function setPatientName($patientName)
    {
        $this->patientName = $patientName;
    }




    /**
     * @return mixed
     */
    public function getCareGiverAssigned()
    {
        return $this->careGiverAssigned;
    }

    /**
     * @param mixed $careGiverAssigned
     */
    public function setCareGiverAssigned($careGiverAssigned)
    {
        $this->careGiverAssigned = $careGiverAssigned;
    }

    /**
     * @return mixed
     */
    public function getShift()
    {
        return $this->shift;
    }

    /**
     * @param mixed $shift
     */
    public function setShift($shift)
    {
        $this->shift = $shift;
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
}