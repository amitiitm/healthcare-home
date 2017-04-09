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

class LeadReferenceDTO{


    public $id;

    public $label;

    public $parentId;

    public $children;

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





    public function convertToDTO($leadReferenceOrm){

        $leadReferenceDTO = new LeadReferenceDTO();
        $leadReferenceDTO->setId($leadReferenceOrm->id);
        $leadReferenceDTO->setLabel($leadReferenceOrm->label);
        $leadReferenceDTO->parentId= $leadReferenceOrm->parent_id;
        if($leadReferenceOrm->childrens->count()>0){
            $tempArra = [];
            foreach ($leadReferenceOrm->childrens as $tempItem){

                array_push($tempArra,$this->convertToDTO($tempItem) );
            }
            $leadReferenceDTO->children = $tempArra;
        }else{
            $leadReferenceDTO->children = array();
        }
        return $leadReferenceDTO;
    }




}