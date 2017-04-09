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

class EmployeeGridItemDTO{

    public $id;

    public $name;

    public $mobile;

    public $departments;

    public function convertToDTO($userObj){
        $employeeGridItemDto = new EmployeeGridItemDTO();
        $employeeGridItemDto->setId($userObj->id);
        $employeeGridItemDto->setName($userObj->name);
        $employeeGridItemDto->setMobile($userObj->phone);


        return $employeeGridItemDto;
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









}