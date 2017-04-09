<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

class ComplaintLogsDTO{


    public $id;

    public $leadId;

    public $user;

    public $userId;

    public $comment;

    public $dateTime;

    public $slackComment;

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



    public function convertToDTO($leadComment){
        $leadCommentDto = new LeadCommentDTO();
        $leadCommentDto->setId($leadComment->id);
        $leadCommentDto->setLeadId($leadComment->lead_id);
        $userMinimalDto = new UserMinimalDTO();
        $leadCommentDto->setUser($userMinimalDto->convertToDto($leadComment->user));
        $leadCommentDto->setComment($leadComment->comment);
        $leadCommentDto->setUserId($leadComment->user_id);
        $leadCommentDto->setDateTime($leadComment->created_at);
        if($leadComment->is_from_slack && $leadComment->slackEmployee){
            $leadCommentDto->setUser($userMinimalDto->convertToDto($leadComment->slackEmployee->user));
        }else if($leadComment->is_from_slack){
            $userSlack  = new UserMinimalDTO();
            $userSlack->setId(0);
            $userSlack->setName("Slack Bot");
            $userSlack->setImageUrl(url("user/profile/0"));
            $leadCommentDto->setUser($userSlack);
        }
        return $leadCommentDto;
    }


}