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

class AuthObject
{
    public $id;

    public $name;

    public $email;

    public $imageUrl;

    public $userTypeId;

    public $isAdmin;

    public $isAuthorizedForTracking;

    public $isAuthorizedForAttendanceReport;


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
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
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
    public function getUserTypeId()
    {
        return $this->userTypeId;
    }

    /**
     * @param mixed $userTypeId
     */
    public function setUserTypeId($userTypeId)
    {
        $this->userTypeId = $userTypeId;
    }

    public function setIsAdmin($isAdmin){
        $this->isAdmin=$isAdmin;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }


    /**
     * @return mixed
     */
    public function getIsAuthorizedForTracking()
    {
        return $this->isAuthorizedForTracking;
    }

    /**
     * @param mixed $isAuthorizedForTracking
     */
    public function setIsAuthorizedForTracking($isAuthorizedForTracking)
    {
        $this->isAuthorizedForTracking = $isAuthorizedForTracking;
    }

    /**
     * @return mixed
     */
    public function getIsAuthorizedForAttendanceReport()
    {
        return $this->isAuthorizedForAttendanceReport;
    }

    /**
     * @param mixed $isAuthorizedForAttendanceReport
     */
    public function setIsAuthorizedForAttendanceReport($isAuthorizedForAttendanceReport)
    {
        $this->isAuthorizedForAttendanceReport = $isAuthorizedForAttendanceReport;
    }




    




}