<?php

namespace App\Contracts\Rest;

interface INotificationRestContract
{
    public function getCustomerNotificationTemplates();
    public function submitCustomerNotification($leadId, $notificationObject);
    public function submitCustomerNotificationAboutCGAssignment($leadId);
    public function submitCustomerNotificationAboutAllAssignment($leadId);
    public function submitCustomerNotificationAboutServiceStart($leadId);
    public function getCustomerNotifications($phone);
}