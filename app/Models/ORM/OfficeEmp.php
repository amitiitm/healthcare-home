<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 6/7/2016
 * Time: 4:46 PM
 */
class OfficeEmp extends Model{
    protected $table = 'table 4';

    public function OfficeEmp(){
        return $this->belongsTo('App\Models\ORM\Feedback');
    }
}