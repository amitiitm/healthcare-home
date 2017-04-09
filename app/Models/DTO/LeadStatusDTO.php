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

class LeadStatusDTO{


    public $id;

    public $leadId;

    public $userId;

    public $user;

    public $status;

    public $statusId;

    public $dateTime;

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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @param mixed $statusId
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
    }





    public function convertToDTO($leadStatus){
        $leadStatusDto  = new LeadStatusDTO();
        $leadStatusDto->setId($leadStatus->id);
        $leadStatusDto->setStatusId($leadStatus->status_id);
        $leadStatusDto->setUserId($leadStatus->user_id);
        $leadStatusDto->setDateTime($leadStatus->created_at);

        if($leadStatus->status){
            $leadStatusDto->setStatus($leadStatus->status);
        }
        if($leadStatus->user){
            $userInfoDto = new UserInfoDTO();
            $leadStatusDto->setUser($userInfoDto->convertToDto($leadStatus->user));
        }

        return $leadStatusDto;
    }


}