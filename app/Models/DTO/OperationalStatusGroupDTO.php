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

class OperationalStatusGroupDTO{

    public $id;

    public $statuses;

    public $label;

    public $sortOrder;

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
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param mixed $statuses
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;
    }



    public function convertToDTO($statusGroupOrm){
        $oprStatuGroupDto = new OperationalStatusGroupDTO();
        $oprStatuGroupDto->setId($statusGroupOrm->id);
        $oprStatuGroupDto->setLabel($statusGroupOrm->label);
        $oprStatuGroupDto->setSortOrder($statusGroupOrm->sort_order);
        //d($statusGroupOrm->statuses);
        if($statusGroupOrm->statuses && $statusGroupOrm->statuses->count()>0){
            $statusDto = new OperationalStatusDTO();
            $listingStatus = array();
            foreach($statusGroupOrm->statuses as $tempItem){
                //as requested by vishal doesn't wanted to show started in lead update status modal
                if($tempItem->slug != 'started'){
                    array_push($listingStatus,$statusDto->convertToDTO($tempItem));
                }
            }
            $oprStatuGroupDto->setStatuses($listingStatus);
        }
        return $oprStatuGroupDto;
    }




}