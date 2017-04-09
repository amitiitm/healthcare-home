<?php
namespace App\Models\DTO\Reports;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class ActiveProjectGridItemDTO{

    public $id;

    public $name;

    public $phone;

    public $email;

    public $location;

    public $refSource;

    public $gender;

    public $ailments;

    public $shiftRequired;
    
    public $shiftRequiredId;

    public $cgName;

    public $cgPhone;

    public function convertToDTO($leadOrm){

        $activeProjectDto = new ActiveProjectGridItemDTO();
        $activeProjectDto->setId($leadOrm->id);

        $activeProjectDto->setName(trim($leadOrm->customer_name." ".$leadOrm->customer_last_name));
        $activeProjectDto->setPhone($leadOrm->phone);
        $activeProjectDto->setEmail($leadOrm->email);
        if($leadOrm->locality){
            $activeProjectDto->setLocation($leadOrm->locality->formatted_address);
        }else{
            $activeProjectDto->setLocation("");
        }
        if($leadOrm->leadReference){
            $activeProjectDto->setRefSource($leadOrm->leadReference->label);
        }else{
            $activeProjectDto->setRefSource("");
        }
        if($leadOrm->patient && $leadOrm->patient->genderItem){
            $activeProjectDto->setGender($leadOrm->patient->genderItem->label);
        }else{
            $activeProjectDto->setGender("");
        }
        $ailmentString = "";
        if($leadOrm->patient && $leadOrm->patient->ailments){
            foreach($leadOrm->patient->ailments as $tempAilment){
                if($ailmentString!=""){
                    $ailmentString .= ", ";
                }
                $ailmentString .= $tempAilment->name;
            }
        }
        $activeProjectDto->setAilments($ailmentString);

        if($leadOrm->patient && $leadOrm->patient->shift){
            $activeProjectDto->setShiftRequired($leadOrm->patient->shift->label);
            $activeProjectDto->setShiftRequiredId($leadOrm->patient->shift_id);
        }else{
            $activeProjectDto->setShiftRequired("");
            $activeProjectDto->setShiftRequiredId("");
        }

        //$cgAssigned = $leadOrm->vendorsAssigned->last();
        $cgAssigned = $leadOrm->primaryVendorsAssigned->last();

        if($cgAssigned){
            $activeProjectDto->setCgName($cgAssigned->name);
            $activeProjectDto->setCgPhone($cgAssigned->phone);
        }else{
            $activeProjectDto->setCgName("");
            $activeProjectDto->setCgPhone("");
        }




        return $activeProjectDto;

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
    public function getRefSource()
    {
        return $this->refSource;
    }

    /**
     * @param mixed $refSource
     */
    public function setRefSource($refSource)
    {
        $this->refSource = $refSource;
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
     * @param mixed $shiftRequiredId
     */
    public function setShiftRequiredId($shiftRequiredId)
    {
        $this->shiftRequiredId = $shiftRequiredId;
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
    public function getCgName()
    {
        return $this->cgName;
    }

    /**
     * @param mixed $cgName
     */
    public function setCgName($cgName)
    {
        $this->cgName = $cgName;
    }

    /**
     * @return mixed
     */
    public function getCgPhone()
    {
        return $this->cgPhone;
    }

    /**
     * @param mixed $cgPhone
     */
    public function setCgPhone($cgPhone)
    {
        $this->cgPhone = $cgPhone;
    }







}