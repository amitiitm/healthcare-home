<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 4/10/16
 * Time: 4:56 PM
 */

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
use App\Models\ORM\DeviceToken;
use App\Models\ORM\UserEmployee;
use App\Models\ORM\VendorTracker;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use Symfony\Component\HttpKernel\Client;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackChat;
use Vluzrmos\SlackApi\Facades\SlackInstantMessage;
use Vluzrmos\SlackApi\Facades\SlackSearch;
use Vluzrmos\SlackApi\Facades\SlackUser;

class GuestLinkController extends Controller
{
    public function index()
    {
        return view('home.home');
    }

    public function slack(){

        //$users = SlackUser::lists(); //all()
        //d($users);

        $channelInfoResponse = SlackChannel::info('C290DP5CP');

        //d($channelInfoResponse);
        $channelMembers = ($channelInfoResponse->channel->members);

        d($channelMembers);
        $slackUserIdList = [];
        foreach($channelMembers as $tempSlackUser){
            array_push($slackUserIdList,$tempSlackUser);
        }
        $userList = UserEmployee::whereIn('slack_user_id',$channelMembers)->get();
        d($userList);
        $mentionString = "";
        foreach($userList as $tempUser){
            $mentionString .= "@".$tempUser->slack_username;
        }
        $mentionString .= " ";



        /*foreach($allChannels->channels as $temp){
            if($temp->name == 'pramati_front_office'){
                $members = $temp->pramati
            }
        }*/
        return;

        $attachments = [];

        $attachments[] = [
            'fallback' => 'Assigned to validate Caregiver to project',
            'text' => 'Assigned to validate Caregiver to project',
            'author_name' => ucfirst("Mohit"),
            'author_link' => url('lead/1166'),
            'title' => 'Assigned as QC',
            'title_link'=>url('lead/1166')
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#slack_testing',
            'as_user' => true,
            'parse' => 'full',
            'text'=>'@mohitgupta kldfj',
            'attachments' => json_encode($attachments),
        ]);
        slack()->post('chat.postMessage', [
            'channel' => '#slack_testing',
            'as_user' => true,
            'parse' => 'full',
            'attachments' => json_encode($attachments),
        ]);

        //SlackChannel::invite('C25B1LGKY','U24R28WQY');
        //SlackChannel::invite('C25BD1YL9','U24R28WQY');
        return;

        echo env('SLACK_WEBHOOK');
        $client = new Client(env('SLACK_WEBHOOK'));
        $responseSlack = $client->to('#slack_testing')
            ->attach([
                'fallback' => 'Assigned to validate Caregiver to project',
                'text' => 'Assigned to validate Caregiver to project',
                'author_name' => ucfirst("Mohit"),
                'author_link' => url('lead/1166'),
                'title' => 'Assigned as QC',
                'title_link'=>url('lead/1166')
            ])->send('test string');
        d($responseSlack);
        echo "dd";

    }

    public function push(){




        $server_key = 'AIzaSyDlrW9n5fYaqjTEeBx8sptgGGLy1WQk3fI';
        $client = new \sngrl\PhpFirebaseCloudMessaging\Client();
        $client->setApiKey($server_key);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $message = new Message();
        $message->setPriority('high');
        $message->addRecipient(new Device('eVDkwSqSdOI:APA91bGzZKf4HWDP-kqrZkb0-MWqmVDEZT1xBCJ748XFie09xzVGOCzTRs4KXbs7_gPfyQdKzreW54eapyWUMBys4m7CxTKjAEBZBAkMDs3ZirwQjsL1PUq-k6Qml_q3iawVz8YLFvuD'));
        $message->setNotification(new Notification('hahaha titsle', 'some bosdy'));

        $response = $client->send($message);
        var_dump($response->getStatusCode());
        var_dump($response->getBody()->getContents());
    }

    public function pushTokens(){
        $getDeviceTokens = VendorTracker::take(100)->with('user')->orderBy('created_at','desc')->get();
        foreach($getDeviceTokens as $tempToken){


            echo "__________________________________";
            $x = array(
                'id'=>$tempToken->id,
                'latitude'=>$tempToken->latitude,
                'longitude'=>$tempToken->longitude,
                'time'=>$tempToken->created_at,

            );
            if($tempToken->user){
                $x['user']=$tempToken->user->name;
            }

            d($x);

            echo "__________________________________";
        }
    }
}