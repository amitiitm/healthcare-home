<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use App\Models\ORM\Equipment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class EquipmentDTO{


    public $id;

    public $name;

    public $detail;

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







    public function convertToDTO($equipment){
        //d($ailment);
        $equipmentDto = new EquipmentDTO();
        $equipmentDto->setId($equipment->id);
        $equipmentDto->setName($equipment->name);
        return $equipmentDto;
    }




}