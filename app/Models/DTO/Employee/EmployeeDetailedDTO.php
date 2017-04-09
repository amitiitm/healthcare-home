<?php
namespace App\Models\DTO\Employee;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class EmployeeDetailedDTO
{

    public $id;

    public $basic;

    public $department;

    public $access;

    public $authentication;


    public function convertToDto($userOrm){


        $employeeDetailedDTO = new EmployeeDetailedDTO();

        $employeeDetailedDTO->setId($userOrm->id);

        $employeeBasicDto = new EmployeeBasicDTO();

        $employeeDetailedDTO->setBasic($employeeBasicDto->convertToDto($userOrm));

        return $employeeDetailedDTO;

    }

    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return mixed
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param mixed $authentication
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return mixed
     */
    public function getBasic()
    {
        return $this->basic;
    }

    /**
     * @param mixed $basic
     */
    public function setBasic($basic)
    {
        $this->basic = $basic;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

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








}