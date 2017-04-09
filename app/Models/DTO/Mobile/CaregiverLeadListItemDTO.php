<?php
namespace App\Models\DTO\Mobile;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CaregiverLeadListItemDTO{


    public $id;

    public $startDate;

    public $shift;

    public $location;

    public $status;

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
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
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







    public function convertToDTO($lead){
        $cgLeadDto = new CaregiverLeadListItemDTO();
        $cgLeadDto->setId($lead->id);
        $cgLeadDto->setStartDate($lead->start_date);
        if($lead->patient && $lead->patient->shift && $lead->patient->shift->label){
            $cgLeadDto->setShift($lead->patient->shift->label);
        }
        $cgLeadDto->setLocation($lead->locality->formatted_address);
        $cgLeadDto->setStatus(rand(-1,1));
        return $cgLeadDto;
    }




}