<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;


 class EmployeeTrackingLocationItemDTO{

     public $latitude;

     public $longitude;

     public $timeStamp;

     public function convertToDTO($location){
         $employeeTrackingLocationItemDto = new EmployeeTrackingLocationItemDTO();
         $employeeTrackingLocationItemDto->setLatitude($location->latitude);
         $employeeTrackingLocationItemDto->setLongitude($location->longitude);
         $employeeTrackingLocationItemDto->setTimeStamp($location->created_at);
         return $employeeTrackingLocationItemDto;
     }

     /**
      * @return mixed
      */
     public function getLatitude()
     {
         return $this->latitude;
     }

     /**
      * @param mixed $latitude
      */
     public function setLatitude($latitude)
     {
         $this->latitude = $latitude;

     }

         /**
          * @return mixed
          */
         public function getLongitude()
     {
         return $this->longitude;
     }

     /**
      * @param mixed $longitude
      * @param mixed $id
      */
     public function setLongitude($longitude)
     {
         $this->longitude = $longitude;
     }

     /**
      * @return mixed
      */
     public function getTimeStamp()
     {
         return $this->timeStamp;
         return $this->name;
     }

     /**
      * @param mixed $timeStamp
      * @param mixed $name
      */
     public function setTimeStamp($timeStamp)
     {
         $this->timeStamp = $timeStamp;
     }


     }