<?php
namespace App\Models\DTO\Mobile;
use App\Models\DTO\ServiceMinimalDTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CustomerLeadItemDTO{


    public $id;

    public $service;

    public $carePlan;

    public $currentStatus;


    public function convertToDTO($lead){
        $leadItemDto  = new CustomerLeadItemDTO();
        $leadItemDto->setId($lead->id);
        $serviceMinimalDto = new ServiceMinimalDTO();
        $leadItemDto->setService($serviceMinimalDto->convertToDto($lead->service));

        $leadCurrentStatus= new CustomerLeadCurrentStatusDTO();
        $leadCarePlan = new CustomerLeadCarePlanDTO();


        $leadItemDto->setCarePlan($leadCarePlan->convertToDTO($lead));
        $leadItemDto->setCurrentStatus($leadCurrentStatus->convertToDTO($lead));

        return $leadItemDto;
    }

    /**
     * @return mixed
     */
    public function getCarePlan()
    {
        return $this->carePlan;
    }

    /**
     * @param mixed $carePlan
     */
    public function setCarePlan($carePlan)
    {
        $this->carePlan = $carePlan;
    }

    /**
     * @return mixed
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param mixed $currentStatus
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;
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
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }
}