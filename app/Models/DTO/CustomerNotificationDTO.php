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

class CustomerNotificationDTO
{


    public $id;

    public $header;

    public $content;

    public $user;

    public $userId;

    public $pushSentAt;

    public $smsSentAt;

    public $dateTime;

    public function convertToDto($notification){
        $customerNotificationDto = new CustomerNotificationDTO();

        $customerNotificationDto->setId($notification->id);
        $customerNotificationDto->setHeader($notification->header);
        $customerNotificationDto->setContent($notification->content);
        $customerNotificationDto->setDateTime($notification->created_at);
        $customerNotificationDto->setPushSentAt($notification->push_sent_at);
        $customerNotificationDto->setSmsSentAt($notification->sms_sent_at);

        $customerNotificationDto->setUserId($notification->user_id);

        $userMinimalDto = new UserMinimalDTO();
        $customerNotificationDto->setUser($userMinimalDto->convertToDto($notification->user));

        return $customerNotificationDto;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
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
    public function getPushSentAt()
    {
        return $this->pushSentAt;
    }

    /**
     * @param mixed $pushSentAt
     */
    public function setPushSentAt($pushSentAt)
    {
        $this->pushSentAt = $pushSentAt;
    }

    /**
     * @return mixed
     */
    public function getSmsSentAt()
    {
        return $this->smsSentAt;
    }

    /**
     * @param mixed $smsSentAt
     */
    public function setSmsSentAt($smsSentAt)
    {
        $this->smsSentAt = $smsSentAt;
    }




}