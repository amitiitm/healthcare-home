<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 6/7/2016
 * Time: 5:30 PM
 */
class UserEmployee extends Model {

    public $table= 'user_employees';

    use SoftDeletes;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }


    public function departments(){
        return $this->belongsToMany('App\Models\ORM\Department','employee_departments','employee_id','department_id')->whereNull('employee_departments.deleted_at')->withTimestamps();
    }

}