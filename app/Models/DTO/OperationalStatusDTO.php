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

class OperationalStatusDTO{


    public $id;

    public $groupId;

    public $group;

    public $slug;

    public $label;

    public $sortOrder;

    public $reasons;

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param mixed $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
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



    public function convertToDTO($statusOrm){
        //d($statusOrm);
        $operationalStatusDto = new OperationalStatusDTO();
        if(!$statusOrm){
            return $operationalStatusDto;
        }
        $operationalStatusDto->setId($statusOrm->id);
        $operationalStatusDto->setGroupId($statusOrm->group_id);
        if($statusOrm->group){
            $statusGroupDto = new OperationalStatusGroupDTO();
            $operationalStatusDto->setGroup($statusGroupDto->convertToDTO($statusOrm->group));
        }
        $reasonDtoList = array();
        if($statusOrm->reasons){
            $operationStatusReasonDto = new OperationalStatusReasonDTO();
            foreach($statusOrm->reasons as $tempReason){
                array_push($reasonDtoList,$operationalStatusDto->convertToDTO($tempReason));
            }
        }
        $operationalStatusDto->setReasons($reasonDtoList);
        $operationalStatusDto->setLabel($statusOrm->label);
        $operationalStatusDto->setSlug($statusOrm->slug);
        $operationalStatusDto->setSortOrder($statusOrm->sort_order);
        return $operationalStatusDto;
    }




}