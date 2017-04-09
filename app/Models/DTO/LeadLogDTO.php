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

class LeadLogDTO{

    public $dateTime;

    public $taskType;

    public $data;

    public $taskUserId;

    public $taskUser;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
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
    public function getTaskType()
    {
        return $this->taskType;
    }

    /**
     * @param mixed $taskType
     */
    public function setTaskType($taskType)
    {
        $this->taskType = $taskType;
    }

    /**
     * @return mixed
     */
    public function getTaskUserId()
    {
        return $this->taskUserId;
    }

    /**
     * @param mixed $taskUserId
     */
    public function setTaskUserId($taskUserId)
    {
        $this->taskUserId = $taskUserId;
    }

    /**
     * @return mixed
     */
    public function getTaskUser()
    {
        return $this->taskUser;
    }

    /**
     * @param mixed $taskUser
     */
    public function setTaskUser($taskUser)
    {
        $this->taskUser = $taskUser;
    }




}