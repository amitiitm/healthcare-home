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

class LeadCommentDTO{


    public $id;

    public $leadId;

    public $user;

    public $userId;

    public $comment;

    public $attachment;

    public $attachmentUrl;

    public $dateTime;

    public $slackComment;

    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param mixed $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
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
    public function getAttachmentUrl()
    {
        return $this->attachmentUrl;
    }

    /**
     * @param mixed $attachmentUrl
     */
    public function setAttachmentUrl($attachmentUrl)
    {
        $this->attachmentUrl = $attachmentUrl;
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
    public function getSlackComment()
    {
        return $this->slackComment;
    }

    /**
     * @param mixed $slackComment
     */
    public function setSlackComment($slackComment)
    {
        $this->slackComment = $slackComment;
    }







    public function convertToDTO($leadComment){
        $leadCommentDto = new LeadCommentDTO();
        $leadCommentDto->setId($leadComment->id);
        $leadCommentDto->setLeadId($leadComment->lead_id);
        $userMinimalDto = new UserMinimalDTO();
        $leadCommentDto->setUser($userMinimalDto->convertToDto($leadComment->user));
        $leadCommentDto->setComment($leadComment->comment);
        $leadCommentDto->setAttachment(false);
        if($leadComment->attachment){
            $leadCommentDto->setAttachment(true);
            $leadCommentDto->setAttachmentUrl(url("attachment/lead/comment/".$leadComment->id));
        }else{
            $leadCommentDto->setAttachmentUrl(null);
        }
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
        $leadCommentDto->setSlackComment($leadComment->is_from_slack);
        return $leadCommentDto;
    }


}