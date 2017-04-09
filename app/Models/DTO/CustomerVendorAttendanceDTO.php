<?php
namespace App\Models\DTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class CustomerVendorAttendanceDTO
{


    public $id;

    public $attendanceDate;

    public $attendanceDateString;

    public $isPresent;

    public $isOnTime;

    public $isWellDressed;

    public $dateTime;

    public function convertToDto($attendance){
        $customerVendorAttendanceDto = new CustomerVendorAttendanceDTO();

        if(!$attendance){
            return $customerVendorAttendanceDto;
        }
        $customerVendorAttendanceDto->setId($attendance->id);
        $customerVendorAttendanceDto->setIsPresent($attendance->is_present);
        $customerVendorAttendanceDto->setIsWellDressed($attendance->is_well_dressed);
        $customerVendorAttendanceDto->setIsOnTime($attendance->is_on_time);
        $customerVendorAttendanceDto->setDateTime($attendance->created_at);
        $customerVendorAttendanceDto->setAttendanceDate($attendance->attendance_date);
        $carbonDate = Carbon::parse($attendance->attendance_date." 00:00:00");
        //d($carbonDate);
        if($carbonDate->isToday()){
            $customerVendorAttendanceDto->setAttendanceDateString("Today (".$carbonDate->toFormattedDateString().")");
        }else if($carbonDate->isYesterday()){
            $customerVendorAttendanceDto->setAttendanceDateString("Yesterday (".$carbonDate->toFormattedDateString().")");
        }else{
            $customerVendorAttendanceDto->setAttendanceDateString($carbonDate->toFormattedDateString());
        }
        return $customerVendorAttendanceDto;
    }

    /**
     * @return mixed
     */
    public function getAttendanceDateString()
    {
        return $this->attendanceDateString;
    }

    /**
     * @param mixed $attendanceDateString
     */
    public function setAttendanceDateString($attendanceDateString)
    {
        $this->attendanceDateString = $attendanceDateString;
    }



    /**
     * @return mixed
     */
    public function getAttendanceDate()
    {
        return $this->attendanceDate;
    }

    /**
     * @param mixed $attendanceDate
     */
    public function setAttendanceDate($attendanceDate)
    {
        $this->attendanceDate = $attendanceDate;
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
    public function getIsOnTime()
    {
        return $this->isOnTime;
    }

    /**
     * @param mixed $isOnTime
     */
    public function setIsOnTime($isOnTime)
    {
        $this->isOnTime = $isOnTime;
    }

    /**
     * @return mixed
     */
    public function getIsPresent()
    {
        return $this->isPresent;
    }

    /**
     * @param mixed $isPresent
     */
    public function setIsPresent($isPresent)
    {
        $this->isPresent = $isPresent;
    }

    /**
     * @return mixed
     */
    public function getIsWellDressed()
    {
        return $this->isWellDressed;
    }

    /**
     * @param mixed $isWellDressed
     */
    public function setIsWellDressed($isWellDressed)
    {
        $this->isWellDressed = $isWellDressed;
    }



}