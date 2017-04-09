<?php

namespace App\Services\Helper;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IFireBasePushHelperContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Jobs\SendQCAssignmentEmail;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;
use Maknz\Slack\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use Vluzrmos\SlackApi\Facades\SlackUserAdmin;

class FireBasePushHelperService implements IFireBasePushHelperContract
{
    use DispatchesJobs;

    protected $userDomainService;

    protected $client;


    public function __construct(IUserDomainContract $userDomainContract)
    {

        $server_key = 'AIzaSyDlrW9n5fYaqjTEeBx8sptgGGLy1WQk3fI';
        $this->client = new \sngrl\PhpFirebaseCloudMessaging\Client();
        $this->client->setApiKey($server_key);
        $this->client->injectGuzzleHttpClient(new \GuzzleHttp\Client());


        $this->userDomainService = $userDomainContract;
    }

    public function sendPushNotification($deviceTokens,$header,$content){
        $message = new Message();
        $message->setPriority('high');
        if(count($deviceTokens)==0){
            return;
        }
        foreach($deviceTokens as $tempToken){
            $message->addRecipient(new Device($tempToken));
        }


        //daads


        $notificationToPush = new Notification($header, $content);
        $notificationToPush->setClickAction('OPEN_ACTIVITY_1');
        $notificationToPush->setSound("notify");
        $notificationToPush->setIcon("bell_icon");
        $message->setNotification($notificationToPush);

        $response = $this->client->send($message);
        //d($response);
        return $response;
    }




}