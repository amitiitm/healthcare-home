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
class Export extends Model{
    protected $table = 'table 2';

    public function Export(){
        return $this->belongsTo('App\Models\ORM\Export');
        //return $this->hasMany('App\Models\ORM\Feedback','employee_id','EmployeeID');
    }
}