<?php

namespace App\Contracts\Helper;

interface ISMSHelperContract
{

    public function sendSMS($phone,$sms,$type=null);

}
