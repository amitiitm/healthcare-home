<?php

namespace App\Contracts\Domain;

interface ICommonDomainContract
{

    public function sendSMS($phone, $message,$type);

}
