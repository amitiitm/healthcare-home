<?php
namespace App\Models\DTO;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\ORM\VendorTracker;
/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class EmployeeTrackingItemDTO{

    public $id;

    public $name;

    public $mobile;

    public $locations;

    public function convertToDTONew($userObj){
        $employeeGridItemDto = new EmployeeTrackingItemDTO();
        $employeeGridItemDto->setId($userObj->id);
        $employeeGridItemDto->setName($userObj->name);
        $employeeGridItemDto->setMobile($userObj->phone);
        $locationArr = [];
        $employeeTrackings = VendorTracker::where('vendor_user_id',$userObj->id)
                ->select('latitude','longitude','vendor_trackers.created_at')
                ->orderBy('id','desc')
                ->limit(1)
                ->get();
        if($employeeTrackings && $employeeTrackings->count()>0){
            foreach($employeeTrackings as $tempLocation){
                $employeeLocationTrackingItemDto = new EmployeeTrackingLocationItemDTO();
                array_push($locationArr,$employeeLocationTrackingItemDto->convertToDTO($tempLocation));
            }
        }
        $employeeGridItemDto->setLocations($locationArr);
        return $employeeGridItemDto;
    }
    
    public function convertToDTO($userObj){
        $employeeGridItemDto = new EmployeeTrackingItemDTO();
        $employeeGridItemDto->setId($userObj->id);
        $employeeGridItemDto->setName($userObj->name);
        $employeeGridItemDto->setMobile($userObj->phone);

        $locationArr = [];
        if($userObj->employeeTracking && $userObj->employeeTracking->count()>0){

            //d($userObj->employeeTracking);
            foreach($userObj->employeeTracking as $tempLocation){
                $employeeLocationTrackingItemDto = new EmployeeTrackingLocationItemDTO();
                array_push($locationArr,$employeeLocationTrackingItemDto->convertToDTO($tempLocation));
            }
        }
        $employeeGridItemDto->setLocations($locationArr);


        return $employeeGridItemDto;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
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