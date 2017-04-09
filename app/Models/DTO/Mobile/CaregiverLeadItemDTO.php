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

class CaregiverLeadItemDTO{


    public $id;

    public $startDate;

    public $shift;

    public $location;

    public $age;

    public $gender;

    public $weight;

    public $ailments;

    public $taskRequired;

    public $status;

    function __construct(){
        $this->id="";
        $this->startDate="";
        $this->gender="";
        $this->age = "";
        $this->weight = "";
        $this->location="";

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
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getAilments()
    {
        return $this->ailments;
    }

    /**
     * @param mixed $ailments
     */
    public function setAilments($ailments)
    {
        $this->ailments = $ailments;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getTaskRequired()
    {
        return $this->taskRequired;
    }

    /**
     * @param mixed $taskRequired
     */
    public function setTaskRequired($taskRequired)
    {
        $this->taskRequired = $taskRequired;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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





    public function convertToDTO($lead){
        $cgLeadDto = new CaregiverLeadItemDTO();
        $cgLeadDto->setId($lead->id);
        $cgLeadDto->setStartDate($lead->start_date);
        if($lead->patient && $lead->patient->shift && $lead->patient->shift->label){
            $cgLeadDto->setShift($lead->patient->shift->label);
        }
        $cgLeadDto->setLocation($lead->locality->formatted_address);
        if($lead->patient){
            $cgLeadDto->setAge($lead->patient->age);
            $cgLeadDto->setWeight($lead->patient->weight);
            $cgLeadDto->setGender($lead->patient->genderItem);
        }

        $cgLeadDto->setStatus(rand(-1,1));
        return $cgLeadDto;
    }




}