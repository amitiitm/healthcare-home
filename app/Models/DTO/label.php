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

class label
{
    public $id;

    public $lable;

    /**
     * @return mixed
     */
    public function getlabel()
    {
        return $this->lable;
    }
}