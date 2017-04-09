<?php

namespace App\Services\Domain;

use App\Contracts\Domain\ICommonDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CommonDomainService implements ICommonDomainContract
{


    public function  sendSMS($phone, $message,$type=null){

        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$phone;
        $url.= '&msgtext='.$message;
        if($type){
            $url.= '&smstype='.$type;
        }else{
            $url.= '&smstype=13';
        }

        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;
        //echo "Url To Hit: ".$urlToUse;

        $ch = curl_init($urlToUse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        //echo $curl_scraped_page;
        curl_close($ch);
        return true;
    }

}