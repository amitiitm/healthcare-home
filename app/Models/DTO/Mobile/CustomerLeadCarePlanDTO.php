<?php
namespace App\Models\DTO\Mobile;
use App\Models\DTO\ServiceMinimalDTO;
use App\Models\DTO\TaskDTO;
use App\Models\DTO\UserMinimalDTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CustomerLeadCarePlanDTO{


    public $patientName;

    public $age;

    public $gender;

    public $weight;

    public $tasks;

    public $ailments;

    public $vendorAssigned;


    public function convertToDTO($lead){
        $customerLeadCarePlanDto = new CustomerLeadCarePlanDTO();

        if($lead->patient){

            $customerLeadCarePlanDto->setPatientName($lead->patient->name);
            $customerLeadCarePlanDto->setAge($lead->patient->age);
            if($lead->patient->genderItem){
                $customerLeadCarePlanDto->setGender($lead->patient->genderItem->label);
            }else{
                $customerLeadCarePlanDto->setGender("NA");
            }

            $customerLeadCarePlanDto->setWeight($lead->patient->weight);
            $customerLeadCarePlanDto->setTasks(array());
            $customerLeadCarePlanDto->setAilments(array());

            $tempTaskArr = array();
            foreach($lead->patient->tasks as $tempTask){
                array_push($tempTaskArr,array(
                    'id'=>$tempTask->id,
                    'label'=>$tempTask->label
                ));
            }
            $customerLeadCarePlanDto->setTasks($tempTaskArr);

            $tempAilmentArr =array();

            foreach($lead->patient->ailments as $ailmentTemp){
                array_push($tempAilmentArr,array(
                    'id'=>$ailmentTemp->id,
                    'label'=>$ailmentTemp->name
                ));
            }

            $customerLeadCarePlanDto->setAilments($tempAilmentArr);
        }else{

        }

        if($lead->vendorsAssigned && count($lead->vendorsAssigned)>0){
            $userMinimalDto = new UserMinimalDTO();
            $customerLeadCarePlanDto->setVendorAssigned($userMinimalDto->convertToDto($lead->vendorsAssigned->last()));
        }

        return $customerLeadCarePlanDto;
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
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
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
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
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

}