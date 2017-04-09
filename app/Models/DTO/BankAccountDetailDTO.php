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

class BankAccountDetailDTO{


    public $id;

    public $name;

    public $bankName;

    public $accountNo;

    public $ifsc;





    public function convertToDTO($bankDetail){

        $bankDetailDto = new BankAccountDetailDTO();


        return $bankDetailDto;
    }




}