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

class EnquiryGridItemDTO
{

    public $id;

    public $name;

    public $email;

    public $phone;

    public $lead;

    public $assignedUserId;

    public $assignedUser;

    public $enquiryDate;

    public $followUpTime;

    /**
     * @return mixed
     */
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }

    /**
     * @param mixed $assignedUser
     */
    public function setAssignedUser($assignedUser)
    {
        $this->assignedUser = $assignedUser;
    }

    /**
     * @return mixed
     */
    public function getAssignedUserId()
    {
        return $this->assignedUserId;
    }

    /**
     * @param mixed $assignedUserId
     */
    public function setAssignedUserId($assignedUserId)
    {
        $this->assignedUserId = $assignedUserId;
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
    public function getEnquiryDate()
    {
        return $this->enquiryDate;
    }

    /**
     * @param mixed $enquiryDate
     */
    public function setEnquiryDate($enquiryDate)
    {
        $this->enquiryDate = $enquiryDate;
    }

    /**
     * @return mixed
     */
    public function getFollowUpTime()
    {
        return $this->followUpTime;
    }

    /**
     * @param mixed $followUpTime
     */
    public function setFollowUpTime($followUpTime)
    {
        $this->followUpTime = $followUpTime;
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
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param mixed $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
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

    public function convertToDto($enquiryOrm){
        $enquiryGridDto = new EnquiryGridItemDTO();
        $enquiryGridDto->setId($enquiryOrm->id);
        $enquiryGridDto->setName($enquiryOrm->name);
        $enquiryGridDto->setEmail($enquiryOrm->email);
        $enquiryGridDto->setPhone($enquiryOrm->phone);
        if($enquiryOrm->lead_id){
            // TODO: Add lead minimal detail
        }else{
            $enquiryGridDto->setLead(null);
        }
        if($enquiryOrm->assigned_user_id){
            // TODO: Add user minimal dto
        }else{
            $enquiryGridDto->setAssignedUser(null);
        }
        if($enquiryOrm->followup_time){
            $enquiryGridDto->setFollowUpTime($enquiryOrm->followup_time);
        }else{
            $enquiryGridDto->setFollowUpTime(null);
        }
        $enquiryGridDto->setEnquiryDate($enquiryOrm->created_at);

        return $enquiryGridDto;
    }



}