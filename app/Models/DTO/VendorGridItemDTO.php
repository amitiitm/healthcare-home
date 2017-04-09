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

class VendorGridItemDTO{

    public $id;

    public $name;

    public $mobile;

    public $age;

    public $weight;

    public $height;

    public $religion;

    public $foodType;

    public $location;

    public $zone;

    public $taskPerformed;

    public $preferredShift;

    public $bankDetail;

    public $smartPhone;

    public $trainingDate;

    public $entryDate;

    public $availability;

    public $gender;

    public $addedByUser;

    public $deployed;

    public $isFlagged;

    public $isBlackListed;


    public function convertToDTO($vendor){
        $vendorDto = new VendorGridItemDTO();

        if($vendor->user){
            $vendorDto->setId($vendor->user->id);
            $vendorDto->setName($vendor->user->name);
            $vendorDto->setMobile($vendor->user->phone);
            $vendorDto->setId($vendor->user->id);
            $vendorDto->setName($vendor->user->name);
            $vendorDto->setMobile($vendor->user->phone);
            $vendorDto->setIsFlagged($vendor->user->is_flagged);
        }

        if($vendor->vendorAvailabilities){
            $vendorDto->setIsBlackListed(false);
            if(count($vendor->vendorAvailabilities) != 0){
                if($vendor->vendorAvailabilities->last()->option_id == 4){
                    $vendorDto->setIsBlackListed(true);
                }
            }
        }

        $vendorDto->setAge($vendor->age);
        $vendorDto->setWeight($vendor->weight);
        $vendorDto->setHeight($vendor->height);

        $vendorDto->setReligion($vendor->religion);

        $vendorDto->setFoodType($vendor->foodType);

        $vendorDto->setZone($vendor->locationOfWork);

        $vendorDto->setPreferredShift($vendor->shift);


        if($vendor->training_attended_date == 'null' || $vendor->training_attended_date==null || $vendor->training_attended_date=='0000-00-00 00:00:00'){
            $vendorDto->setTrainingDate('');
        }else {
            $vendorDto->setTrainingDate($vendor->training_attended_date);
        }
       // $vendorDto->setTrainingDate($vendor->training_attended_date);

        $vendorAvailability = $vendor->vendorAvailabilities->last();
        if($vendorAvailability && $vendorAvailability->available==1){
            $vendorDto->setAvailability("Available");
        }else if($vendorAvailability && $vendorAvailability->available==0){
            $vendorDto->setAvailability("Un-Available");
        }else{
            $vendorDto->setAvailability("");
        }

        if($vendor->genderObject){
            $vendorDto->setGender($vendor->genderObject->label);
        }else{
            $vendorDto->setGender("");
        }


        $vendorDto->setEntryDate($vendor->created_at);

        if($vendor->addedByUser){

            $userMinimalDto = new UserMinimalDTO();
            $vendorDto->setAddedByUser($userMinimalDto->convertToDto($vendor->addedByUser));

        }


        return $vendorDto;
    }

    /**
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param mixed $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
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
    public function getBankDetail()
    {
        return $this->bankDetail;
    }

    /**
     * @param mixed $bankDetail
     */
    public function setBankDetail($bankDetail)
    {
        $this->bankDetail = $bankDetail;
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
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
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
    public function getPreferredShift()
    {
        return $this->preferredShift;
    }

    /**
     * @param mixed $preferredShift
     */
    public function setPreferredShift($preferredShift)
    {
        $this->preferredShift = $preferredShift;
    }

    /**
     * @return mixed
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param mixed $religion
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    }

    /**
     * @return mixed
     */
    public function getSmartPhone()
    {
        return $this->smartPhone;
    }

    /**
     * @param mixed $smartPhone
     */
    public function setSmartPhone($smartPhone)
    {
        $this->smartPhone = $smartPhone;
    }

    /**
     * @return mixed
     */
    public function getTaskPerformed()
    {
        return $this->taskPerformed;
    }

    /**
     * @param mixed $taskPerformed
     */
    public function setTaskPerformed($taskPerformed)
    {
        $this->taskPerformed = $taskPerformed;
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
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getFoodType()
    {
        return $this->foodType;
    }

    /**
     * @param mixed $foodType
     */
    public function setFoodType($foodType)
    {
        $this->foodType = $foodType;
    }

    /**
     * @return mixed
     */
    public function getTrainingDate()
    {
        return $this->trainingDate;
    }

    /**
     * @param mixed $trainingDate
     */
    public function setTrainingDate($trainingDate)
    {
        $this->trainingDate = $trainingDate;
    }

    /**
     * @return mixed
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * @param mixed $entryDate
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;
    }

    /**
     * @return mixed
     */
    public function getAddedByUser()
    {
        return $this->addedByUser;
    }

    /**
     * @param mixed $addedByUser
     */
    public function setAddedByUser($addedByUser)
    {
        $this->addedByUser = $addedByUser;
    }

    /**
     * @return mixed
     */
    public function getDeployed()
    {
        return $this->deployed;
    }

    /**
     * @param mixed $deployed
     */
    public function setDeployed($deployed)
    {
        $this->deployed = $deployed;
    }

    /**
     * @param mixed $isFlagged
     */
    public function setIsFlagged($isFlagged)
    {
        $this->isFlagged = $isFlagged;
    }

    public function setIsBlackListed($blackListStatus)
    {
        $this->isBlackListed = $blackListStatus;
    }













}