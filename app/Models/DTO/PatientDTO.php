<?php
namespace App\Models\DTO;
use App\Models\ORM\AgeRange;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class PatientDTO{

    public $id;

    public $leadId;

    public $name;

    public $gender;

    public $age;

    public $weight;

    public $isOnEquipmentSupport;

    public $equipment;

    public $equipments;

    public $shiftRequired;

    public $mobility;

    public $illness;

    public $physicalCondition;

    public $morningWakeUpTime;

    public $breakfastTime;

    public $lunchTime;

    public $dinnerTime;

    public $walkTiming;

    public $walkLocation;

    public $religionPreference;

    public $religionPreferred;

    public $agePreferece;

    public $agePreferred;

    public $foodPreference;

    public $foodPreferred;

    public $genderPreference;

    public $genderPreferred;

    public $languagePreference;

    public $languagePreferred;

    public $ailments;

    public $tasks;

    public $medicine;

    public $doctorName;

    public $hospitalName;

    public $otherAilment;


    /**
     * @return mixed
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * @param mixed $medicine
     */
    public function setMedicine($medicine)
    {
        $this->medicine = $medicine;
    }

    public function setDoctor($doctorName)
    {
        $this->doctorName = $doctorName;
    }

    public function setHospital($hospitalName)
    {
        $this->hospitalName = $hospitalName;
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
    public function getAgePreferece()
    {
        return $this->agePreferece;
    }

    /**
     * @param mixed $agePreferece
     */
    public function setAgePreferece($agePreferece)
    {
        $this->agePreferece = $agePreferece;
    }

    /**
     * @return mixed
     */
    public function getAgePreferred()
    {
        return $this->agePreferred;
    }

    /**
     * @param mixed $agePreferred
     */
    public function setAgePreferred($agePreferred)
    {
        $this->agePreferred = $agePreferred;
    }

    /**
     * @return mixed
     */
    public function getBreakfastTime()
    {
        return $this->breakfastTime;
    }

    /**
     * @param mixed $breakfastTime
     */
    public function setBreakfastTime($breakfastTime)
    {
        $this->breakfastTime = $breakfastTime;
    }

    /**
     * @return mixed
     */
    public function getDinnerTime()
    {
        return $this->dinnerTime;
    }

    /**
     * @param mixed $dinnerTime
     */
    public function setDinnerTime($dinnerTime)
    {
        $this->dinnerTime = $dinnerTime;
    }

    /**
     * @return mixed
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * @param mixed $equipment
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
    }

    /**
     * @return mixed
     */
    public function getFoodPreference()
    {
        return $this->foodPreference;
    }

    /**
     * @param mixed $foodPreference
     */
    public function setFoodPreference($foodPreference)
    {
        $this->foodPreference = $foodPreference;
    }

    /**
     * @return mixed
     */
    public function getFoodPreferred()
    {
        return $this->foodPreferred;
    }

    /**
     * @param mixed $foodPreferred
     */
    public function setFoodPreferred($foodPreferred)
    {
        $this->foodPreferred = $foodPreferred;
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
    public function getGenderPreference()
    {
        return $this->genderPreference;
    }

    /**
     * @param mixed $genderPreference
     */
    public function setGenderPreference($genderPreference)
    {
        $this->genderPreference = $genderPreference;
    }

    /**
     * @return mixed
     */
    public function getGenderPreferred()
    {
        return $this->genderPreferred;
    }

    /**
     * @param mixed $genderPreferred
     */
    public function setGenderPreferred($genderPreferred)
    {
        $this->genderPreferred = $genderPreferred;
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
    public function getIllness()
    {
        return $this->illness;
    }

    /**
     * @param mixed $illness
     */
    public function setIllness($illness)
    {
        $this->illness = $illness;
    }

    /**
     * @return mixed
     */
    public function getIsOnEquipmentSupport()
    {
        return $this->isOnEquipmentSupport;
    }

    /**
     * @param mixed $isOnEquipmentSupport
     */
    public function setIsOnEquipmentSupport($isOnEquipmentSupport)
    {
        $this->isOnEquipmentSupport = $isOnEquipmentSupport;
    }

    /**
     * @return mixed
     */
    public function getLanguagePreference()
    {
        return $this->languagePreference;
    }

    /**
     * @param mixed $languagePreference
     */
    public function setLanguagePreference($languagePreference)
    {
        $this->languagePreference = $languagePreference;
    }

    /**
     * @return mixed
     */
    public function getLanguagePreferred()
    {
        return $this->languagePreferred;
    }

    /**
     * @param mixed $languagePreferred
     */
    public function setLanguagePreferred($languagePreferred)
    {
        $this->languagePreferred = $languagePreferred;
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
    public function getLunchTime()
    {
        return $this->lunchTime;
    }

    /**
     * @param mixed $lunchTime
     */
    public function setLunchTime($lunchTime)
    {
        $this->lunchTime = $lunchTime;
    }

    /**
     * @return mixed
     */
    public function getMobility()
    {
        return $this->mobility;
    }

    /**
     * @param mixed $mobility
     */
    public function setMobility($mobility)
    {
        $this->mobility = $mobility;
    }

    /**
     * @return mixed
     */
    public function getMorningWakeUpTime()
    {
        return $this->morningWakeUpTime;
    }

    /**
     * @param mixed $morningWakeUpTime
     */
    public function setMorningWakeUpTime($morningWakeUpTime)
    {
        $this->morningWakeUpTime = $morningWakeUpTime;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhysicalCondition()
    {
        return $this->physicalCondition;
    }

    /**
     * @param mixed $physicalCondition
     */
    public function setPhysicalCondition($physicalCondition)
    {
        $this->physicalCondition = $physicalCondition;
    }

    /**
     * @return mixed
     */
    public function getReligionPreference()
    {
        return $this->religionPreference;
    }

    /**
     * @param mixed $religionPreference
     */
    public function setReligionPreference($religionPreference)
    {
        $this->religionPreference = $religionPreference;
    }

    /**
     * @return mixed
     */
    public function getReligionPreferred()
    {
        return $this->religionPreferred;
    }

    /**
     * @param mixed $religionPreferred
     */
    public function setReligionPreferred($religionPreferred)
    {
        $this->religionPreferred = $religionPreferred;
    }

    /**
     * @return mixed
     */
    public function getShiftRequired()
    {
        return $this->shiftRequired;
    }

    /**
     * @param mixed $shiftRequired
     */
    public function setShiftRequired($shiftRequired)
    {
        $this->shiftRequired = $shiftRequired;
    }

    /**
     * @return mixed
     */
    public function getWalkLocation()
    {
        return $this->walkLocation;
    }

    /**
     * @param mixed $walkLocation
     */
    public function setWalkLocation($walkLocation)
    {
        $this->walkLocation = $walkLocation;
    }

    /**
     * @return mixed
     */
    public function getWalkTiming()
    {
        return $this->walkTiming;
    }

    /**
     * @param mixed $walkTiming
     */
    public function setWalkTiming($walkTiming)
    {
        $this->walkTiming = $walkTiming;
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

    public function getOtherAilment()
    {
        return $this->otherAilment;
    }

    public function setOtherAilment($otherAilment)
    {
        $this->otherAilment = $otherAilment;
    }

    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * @param mixed $ailments
     */
    public function setEquipments($equipments)
    {
        $this->equipments = $equipments;
    }

    public function convertToDTO($patientOrm){

//        d($patientOrm);


        $patientInfo= new PatientDTO();
        if(!$patientOrm || !$patientOrm->id){
            return $patientOrm;
        }

        $patientInfo->setOtherAilment($patientOrm->other_ailment);
        $patientInfo->setId($patientOrm->id);
        $patientInfo->setLeadId($patientOrm->lead_id);
        $patientInfo->setName($patientOrm->name);
        $patientInfo->setGender($patientOrm->genderItem);
        $patientInfo->setAge($patientOrm->age);
        $patientInfo->setWeight($patientOrm->weight);
        $patientInfo->setIsOnEquipmentSupport($patientOrm->is_on_equipment);
        $patientInfo->setEquipment($patientOrm->equipment);
        // TODO: shift detail
        $patientInfo->setShiftRequired($patientOrm->shift);

         if($patientOrm->mobilityItem){
            $mobilityDto = new MobilityDTO();
            $patientInfo->setMobility($mobilityDto->convertToDTO($patientOrm->mobilityItem));
        }

        $patientInfo->setIllness($patientOrm->illness);
        $patientInfo->setPhysicalCondition($patientOrm->physical_condition);
        $patientInfo->setMorningWakeUpTime($patientOrm->morning_wakeup_time);
        $patientInfo->setBreakfastTime($patientOrm->breakfast_time);
        $patientInfo->setLunchTime($patientOrm->lunch_time);
        $patientInfo->setDinnerTime($patientOrm->dinner_time);
        $patientInfo->setWalkTiming($patientOrm->walk_time);
        $patientInfo->setWalkLocation($patientOrm->walk_location);
        $patientInfo->setReligionPreference($patientOrm->religion_preference);
        $patientInfo->setReligionPreferred($patientOrm->religionPreferred);
        $patientInfo->setGenderPreference($patientOrm->gender_preference);
        $patientInfo->setGenderPreferred($patientOrm->genderPreferred);
        //d($patientOrm);
        $patientInfo->setLanguagePreference($patientOrm->language_preference);
        $patientInfo->setLanguagePreferred($patientOrm->languagePreferred);
        $patientInfo->setAgePreferece($patientOrm->age_preference);
        $patientInfo->setAgePreferred($patientOrm->agePreferred);
        $patientInfo->setFoodPreference($patientOrm->food_preference);
        $patientInfo->setFoodPreferred($patientOrm->foodPreferred);


        $patientInfo->setTasks(array());
        if($patientOrm->tasks->count()>0){
            $taskDto = new TaskDTO();
            $tempArray=[];
            foreach($patientOrm->tasks as $tempAilment){
                array_push($tempArray,$taskDto->convertToDTO($tempAilment));
            }
            $patientInfo->setTasks($tempArray);
        }
        $patientInfo->setAilments(array());
//echo count($patientOrm->ailments);
        if($patientOrm->ailments->count()>0){
            $ailmentDto = new AilmentDTO();
            $tempArray=[];
            foreach($patientOrm->ailments as $tempAilment){
                array_push($tempArray,$ailmentDto->convertToDTO($tempAilment));
            }
            $patientInfo->setAilments($tempArray);
        }

        $patientInfo->setEquipments(array());
        if($patientOrm->equipments->count()>0){
            $equipmentDto = new EquipmentDTO();
            $tempArray=[];
            foreach($patientOrm->equipments as $tempEquipment){
                array_push($tempArray,$equipmentDto->convertToDTO($tempEquipment));
            }
            $patientInfo->setEquipments($tempArray);
        }
        
        $patientInfo->setMedicine($patientOrm->medicine);
        
        $patientInfo->setDoctor($patientOrm->doctor_name);

        $patientInfo->setHospital($patientOrm->hospital_name);


        return $patientInfo;


    }




}