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

class LeadVendorDTO{


    public $id;

    public $leadId;

    public $assignee;

    public $assignedBy;

    public $dateTime;

    public $userId;

    /**
     * @return mixed
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * @param mixed $assignedBy
     */
    public function setAssignedBy($assignedBy)
    {
        $this->assignedBy = $assignedBy;
    }

    /**
     * @return mixed
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param mixed $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
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





    public function convertToDTO($leadEmployee){
        $leadEmployeeDto  = new LeadEmployeeDTO();
        $leadEmployeeDto->setId($leadEmployee->id);
        $userMinimalDto = new UserMinimalDTO();
        $leadEmployeeDto->setAssignee($userMinimalDto->convertToDto($leadEmployee->assignee));
        $leadEmployeeDto->setAssignedBy($userMinimalDto->convertToDto($leadEmployee->assignedBy));
        $leadEmployeeDto->setDateTime($leadEmployee->created_at);
        $leadEmployeeDto->setLeadId($leadEmployee->lead_id);
        $leadEmployeeDto->setUserId($leadEmployee->assigned_by_user_id);


        return $leadEmployeeDto;
    }


}