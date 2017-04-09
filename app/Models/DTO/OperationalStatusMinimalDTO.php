<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class OperationalStatusMinimalDTO{


    public $id;

    public $slug;

    public $label;

    public $reasons;

    public $sortOrder;



    public function convertToDTO($statusOrm){
        //d($statusOrm);
        $operationalStatusDto = new OperationalStatusMinimalDTO();
        if(!$statusOrm){
            return $operationalStatusDto;
        }
        $operationalStatusDto->setId($statusOrm->id);
        $reasonDtoList = array();
        if($statusOrm->reasons){
            $operationStatusReasonDto = new OperationalStatusReasonDTO();
            foreach($statusOrm->reasons as $tempReason){
                array_push($reasonDtoList,$operationStatusReasonDto->convertToDTO($tempReason));
            }
        }
        $operationalStatusDto->setReasons($reasonDtoList);
        $operationalStatusDto->setLabel($statusOrm->label);
        $operationalStatusDto->setSlug($statusOrm->slug);
        $operationalStatusDto->setSortOrder($statusOrm->sort_order);
        return $operationalStatusDto;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * @param mixed $reasons
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }






}