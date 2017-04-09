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

class EmployeeDetailDTO
{


    public $id;

    public $name;

    public $email;

    public $phone;

    public $departments;

    public $address;

    public $slackUsername;

    public $userImage;



    public function convertToDto($userOrm){

        $employeeDetailDto = new EmployeeDetailDTO();

        $employeeDetailDto->setId($userOrm->id);
        $employeeDetailDto->setName($userOrm->name);
        $employeeDetailDto->setPhone($userOrm->phone);
        $employeeDetailDto->setEmail($userOrm->email);
        $departmentList = array();

       // dd($userOrm);
        $employeeDetailDto->setUserImage(url("user/profile/".$userOrm->id));

        if(null != $userOrm->employeeInfo){
            $employeeDetailDto->setAddress($userOrm->employeeInfo->address);
            $employeeDetailDto->setSlackUsername($userOrm->employeeInfo->slackUsername);
            foreach($userOrm->employeeInfo->departments as $tempDepartment){

            }

        }






        return $employeeDetailDto;
    }

    /**
     * @return mixed
     */
    public function getUserImage()
    {
        return $this->userImage;
    }

    /**
     * @param mixed $userImage
     */
    public function setUserImage($userImage)
    {
        $this->userImage = $userImage;
    }



    /**
     * @return mixed
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * @param mixed $departments
     */
    public function setDepartments($departments)
    {
        $this->departments = $departments;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getSlackUsername()
    {
        return $this->slackUsername;
    }

    /**
     * @param mixed $slackUsername
     */
    public function setSlackUsername($slackUsername)
    {
        $this->slackUsername = $slackUsername;
    }






}