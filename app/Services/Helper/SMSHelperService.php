<?php

namespace App\Services\Helper;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Contracts\Helper\ISMSHelperContract;
use App\Jobs\SendQCAssignmentEmail;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maknz\Slack\Client;
use Vluzrmos\SlackApi\Facades\SlackUserAdmin;

class SMSHelperService implements ISMSHelperContract
{
    use DispatchesJobs;

    protected $userDomainService;


    public function __construct(IUserDomainContract $userDomainContract)
    {
        $this->userDomainService = $userDomainContract;
    }

    public function sendSMS($phone,$sms, $type=null){
        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$phone;
        $url.= '&msgtext='.urlencode($sms);
        if($type){
            $url.= '&smstype='.$type;
        }else{
            $url.= '&smstype=13';
        }

        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;
       // echo "Url To Hit: ".$urlToUse;

        $ch = curl_init($urlToUse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        Log::info('SMS Sending Response');
        Log::info(json_encode($curl_scraped_page));
        curl_close($ch);
        return true;
    }



}