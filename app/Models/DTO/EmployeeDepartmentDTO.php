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

class EmployeeDepartmentDTO{


    public $id;

    public $department;

    public $isManager;



    public function convertToDTO($employeeDepartment){
        $employeeDepartmentDto = new EmployeeDepartmentDTO();
        $departmentDto = new DepartmentDTO();

        

        return $employeeDepartmentDto;
    }




}