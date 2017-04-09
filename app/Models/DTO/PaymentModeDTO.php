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

class PaymentModeDTO{


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



    public function convertToDTO($paymentModeOrm){
        $paymentModeDto = new PaymentModeDTO();
        $paymentModeDto->setId($paymentModeOrm->id);
        $paymentModeDto->setLabel($paymentModeOrm->label);
        return $paymentModeDto;
    }




}