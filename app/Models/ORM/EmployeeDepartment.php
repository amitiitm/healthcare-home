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
class EmployeeDepartment extends Model {

    public $table= 'employee_departments';

    use SoftDeletes;



}