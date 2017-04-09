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

class OperationalStatusReasonDTO{


    public $id;

    public $label;

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



    public function convertToDTO($statusReasonOrm){
        $statusReasonDto = new OperationalStatusReasonDTO();
        if(!$statusReasonOrm){ return $statusReasonDto; }
        $statusReasonDto->setId($statusReasonOrm->id);
        $statusReasonDto->setLabel($statusReasonOrm->label);
        return $statusReasonDto;
    }




}