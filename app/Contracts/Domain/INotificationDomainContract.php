<?php

namespace App\Contracts\Domain;

interface INotificationDomainContract
{

    public function getNotificationTemplates();
    public function getDeviceTokenByPhone($phoneNo);
    public function submitCustomerNotification($leadId, $phoneNo, $header, $content,$loggedInUser);
    public function createNewTemplate($templateName, $header, $content);



    public function markCustomerNotificationPushSent($notificationId, $timeStamp);
    public function markCustomerNotificationSMSSent($notificationId, $timeStamp);


    public function getCustomerNotification($phone);
}
