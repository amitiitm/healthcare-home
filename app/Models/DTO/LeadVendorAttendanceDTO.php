<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use App\Models\ORM\OperationalStatus;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class LeadVendorAttendanceDTO{


    public $id;

    public $leadId;

    public $vendor;

    public $user;

    public $attendance;

    public $date;

    public $price;

    public $comment;
    public $dateTime;

    public $userId;


    public function convertToDTO($leadVendorAttendance){
        $leadVendorAttendanceDto = new LeadVendorAttendanceDTO();

        $leadVendorAttendanceDto->setId($leadVendorAttendance->id);
        $leadVendorAttendanceDto->setAttendance($leadVendorAttendance->is_present);
        $leadVendorAttendanceDto->setPrice($leadVendorAttendance->price);
        $leadVendorAttendanceDto->setDate($leadVendorAttendance->date);
        $leadVendorAttendanceDto->setComment($leadVendorAttendance->comment);
        $leadVendorAttendanceDto->setLeadId($leadVendorAttendance->lead_id);

        $userMinimalDto = new UserMinimalDTO();
        if($leadVendorAttendance->user){
            $leadVendorAttendanceDto->setUser($userMinimalDto->convertToDto($leadVendorAttendance->user));
        }
        if($leadVendorAttendance->vendor){
            $leadVendorAttendanceDto->setVendor($userMinimalDto->convertToDto($leadVendorAttendance->vendor));
        }

        $leadVendorAttendanceDto->setDateTime($leadVendorAttendance->created_at);

        $leadVendorAttendanceDto->setUserId($leadVendorAttendance->marked_by);
        $leadVendorAttendanceDto->setMedium($leadVendorAttendance->medium);

        return $leadVendorAttendanceDto;
    }

    /**
     * @return mixed
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * @param mixed $attendance
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
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
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * @param mixed $leadId
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $medium
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }




}