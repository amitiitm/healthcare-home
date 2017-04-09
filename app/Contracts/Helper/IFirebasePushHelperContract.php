<?php

namespace App\Contracts\Helper;

interface IFireBasePushHelperContract
{
    public function sendPushNotification($deviceTokens,$header,$content);
}
