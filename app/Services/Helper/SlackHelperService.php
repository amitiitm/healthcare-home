<?php

namespace App\Services\Helper;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Jobs\SendQCAssignmentEmail;
use App\Models\Article;
use App\Models\Enums\SCConstants;
use App\Models\ORM\UserEmployee;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maknz\Slack\Client;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUserAdmin;

class SlackHelperService implements ISlackHelperContract
{
    use DispatchesJobs;

    protected $userDomainService;

    public function __construct(IUserDomainContract $userDomainContract)
    {
        $this->userDomainService = $userDomainContract;
    }


    public function generateSlackForUser($userOrm){
        $firstName = "";
        $lastName = "";
        $fullName = trim($userOrm->name);
        if(str_word_count($fullName)>1){
            $exploded = explode(" ",$fullName);
            $firstName = $exploded[0];
            $lastName = substr($fullName,strpos($fullName," ")+1);
        }else{
            $firstName = $fullName;
            $lastName = '';
        }
        $userArr = [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
        SlackUserAdmin::invite($userOrm->email,$userArr );
        return true;
    }

    public function newLeadNotification($leadOrm){
        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'seema.chauhan@pramaticare.com',
                'robin@pramaticare.com',
                'richa@pramaticare.com',
                'kajal@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " please validate the newly created lead.";
        $client = new Client(env('SLACK_WEBHOOK'));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'fallback' => 'Validate new lead',
            'text' => 'Validate new lead',
            'author_name' => "Validate new Lead",
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'Validate Lead',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);



        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'robin@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " please assign employee to the newly created lead.";
        $client = new Client(env('SLACK_WEBHOOK'));

        $attachments[] = [
            'fallback' => 'Assign employee to lead',
            'text' => 'Assign employee to lead',
            'author_name' => "Assign Employee To Lead",
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'Assigned as Employee',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);

        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

    }


    public function employeeAssignedNotification($leadOrm,$assignedUser){
        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                $assignedUser->email
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " is assigned as employee";
        $client = new Client(env('SLACK_WEBHOOK'));

        $attachments[] = [
            'fallback' => 'Assigned as employee to assign Caregiver',
            'text' => 'Assigned as employee to assign Caregiver',
            'author_name' => $assignedUser->name,
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'Assign Caregivers To Lead',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
    }

    public function qcAssignedNotification($leadOrm,$assignedUser){
        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                $assignedUser->email
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " is assigned as QC";
        $client = new Client(env('SLACK_WEBHOOK'));

        $attachments[] = [
            'fallback' => 'Assigned as QC to validate caregivers',
            'text' => 'Assigned as QC to validate caregivers',
            'author_name' => $assignedUser->name,
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'Validate CG assigned to project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
    }

    public function fieldAssignedNotification($leadOrm,$assignedUser){
        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                $assignedUser->email
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " is assigned as field executive";
        $client = new Client(env('SLACK_WEBHOOK'));

        $attachments[] = [
            'fallback' => 'Assigned as field executive for project',
            'text' => 'Assigned as field executive for project',
            'author_name' => $assignedUser->name,
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
    }


    public function cgAssignedNotification($leadOrm,$assignedUser,$isPrimary){
        // notification to validate lead
        $frontDesk = [];
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'meenu@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " please validate the caregivers assigned to project";
        $client = new Client(env('SLACK_WEBHOOK'));

        $attachments[] = [
            'fallback' => ($isPrimary?'Primary':'Backup').' caregiver is assigned to the ',
            'text' => ($isPrimary?'Primary':'Backup').' caregiver is assigned to the ',
            'author_name' => $assignedUser->name,
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'Validate CG assigned to project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
    }


    public function projectStartNotification($leadOrm){
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'seema.chauhan@pramaticare.com',
                'robin@pramaticare.com',
                'richa@pramaticare.com',
                'kajal@pramaticare.com',
                'seema.chauhan@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString .= " project started please inform customer and start billing.";
        $client = new Client(env('SLACK_WEBHOOK'));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'fallback' => 'Inform customer about service start',
            'text' => 'Inform customer about service start',
            'author_name' => "View Started Project",
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Started Project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
    }

    public function projectClosureRequestNotification($leadOrm,$requestObject){

       // Log::json(json_encode($requestObject));
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'seema.chauhan@pramaticare.com',
                'robin@pramaticare.com',
                'richa@pramaticare.com',
                'kajal@pramaticare.com',
                'seema.chauhan@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString = "Customer requested to close service with Pramati Care.";
        ///// TODO:
        //Log::info(json_encode($slackString));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'author_name' => "View Project",
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Project',
            'title_link'=> url('lead/'.$leadOrm->id),
            'color'=> '#ff0000'
        ];
/*
        $attachments['fields'] =  [];
        if($requestObject->reason){
            array_push($attachments['fields'],[
                    "title"=> "Reason",
                    "value"=> $requestObject->reason->label,
                    "short"=> false
                ]);
        }
        if($requestObject->reason){
            array_push($attachments['fields'],[
                    "title"=> "Issue",
                    "value"=> $requestObject->issue->label,
                    "short"=> false

            ]);
        }*/


        //d(json_encode($attachments));
//using new slack helper function (release 0.4.3)

        $slackResponse = slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);
       // Log::info(json_encode($slackResponse));

        if(env('APP_ENV')=="production"){
            $channelInfoResponse = SlackChannel::info('C290DP5CP');
            //d($channelInfoResponse);
            $channelMembers = ($channelInfoResponse->channel->members);

            $slackUserIdList = [];
            foreach($channelMembers as $tempSlackUser){
                array_push($slackUserIdList,$tempSlackUser);
            }
            $userList = UserEmployee::whereIn('slack_user_id',$channelMembers)->get();
            $mentionString = "";
            foreach($userList as $tempUser){
                $mentionString .= "@".$tempUser->slack_username." ";
            }
            $slackString = $mentionString." Customer requested to close service with Pramati Care";
            slack()->post('chat.postMessage', [
                'channel' => '#pramati_front_office',
                'as_user' => true,
                'parse' => 'full',
                'text'=> $slackString,
                'attachments' => json_encode($attachments),
            ]);
        }
    }

    public function cgNotReachedNotification($leadOrm,$requestObject){
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'seema.chauhan@pramaticare.com',
                'robin@pramaticare.com',
                'richa@pramaticare.com',
                'kajal@pramaticare.com',
                'seema.chauhan@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString = "Caregiver not reached for service";
        Log::info($slackString);
        $client = new Client(env('SLACK_WEBHOOK'));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'author_name' => trim($leadOrm->customer_name." ".$leadOrm->customer_last_name),
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        $slackResponse = slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);

        if(env('APP_ENV')=="production"){

            $channelInfoResponse = SlackChannel::info('C290DP5CP');

            //d($channelInfoResponse);
            $channelMembers = ($channelInfoResponse->channel->members);

            $slackUserIdList = [];
            foreach($channelMembers as $tempSlackUser){
                array_push($slackUserIdList,$tempSlackUser);
            }
            $userList = UserEmployee::whereIn('slack_user_id',$channelMembers)->get();
            $mentionString = "";
            foreach($userList as $tempUser){
                $mentionString .= "@".$tempUser->slack_username." ";
            }

            $slackString = $mentionString." Customer notified that CG is not reached";


            slack()->post('chat.postMessage', [
                'channel' => '#pramati_front_office',
                'as_user' => true,
                'parse' => 'full',
                'text'=> $slackString,
                'attachments' => json_encode($attachments),
            ]);
        }
    }

    public function projectRestartRequestNotification($leadOrm,$requestObject){
        if(env('APP_ENV')=="production") {
            $frontDesk = [
                'seema.chauhan@pramaticare.com',
                'robin@pramaticare.com',
                'richa@pramaticare.com',
                'kajal@pramaticare.com',
                'seema.chauhan@pramaticare.com'
            ];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }
        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString = "Customer requested to restart service";
        Log::info($slackString);
        $client = new Client(env('SLACK_WEBHOOK'));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'author_name' => trim($leadOrm->customer_name." ".$leadOrm->customer_last_name),
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        $slackResponse = slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);

        if(env('APP_ENV')=="production"){

            $channelInfoResponse = SlackChannel::info('C290DP5CP');

            //d($channelInfoResponse);
            $channelMembers = ($channelInfoResponse->channel->members);

            $slackUserIdList = [];
            foreach($channelMembers as $tempSlackUser){
                array_push($slackUserIdList,$tempSlackUser);
            }
            $userList = UserEmployee::whereIn('slack_user_id',$channelMembers)->get();
            $mentionString = "";
            foreach($userList as $tempUser){
                $mentionString .= "@".$tempUser->slack_username." ";
            }

            $slackString = $mentionString." Customer requested to restart service with Pramati Care";


            slack()->post('chat.postMessage', [
                'channel' => '#pramati_front_office',
                'as_user' => true,
                'parse' => 'full',
                'text'=> $slackString,
                'attachments' => json_encode($attachments),
            ]);
        }

    }

    public function customerSubmittedCaregiverAttendanceNotification($leadOrm,$requestObject){
        if(env('APP_ENV')=="production") {
            $frontDesk=[];
        }else{
            $frontDesk = [env('TESTING_MAIL')];
        }

        $employeeUserList = $this->userDomainService->getEmployeeByIdList($this->userDomainService->getUserIdByEmailList($frontDesk));
        $slackString = '';
        foreach($employeeUserList as $tempUser){
            if($tempUser->employeeInfo==null){
                continue;
            }
            if($tempUser->employeeInfo->slack_username==null){
                continue;
            }
            $slackString.="@".$tempUser->employeeInfo->slack_username." ";
        }
        $slackString = "Customer submitted CG attendance";
        Log::info($slackString);
        $client = new Client(env('SLACK_WEBHOOK'));
        //$responseSlack = $client->from("CRM")->to($leadOrm->slack_channel_name)->attach()->send($slackString);

        $attachments[] = [
            'author_name' => trim($leadOrm->customer_name." ".$leadOrm->customer_last_name),
            'author_link' => url('lead/'.$leadOrm->id),
            'title'=>'View Project',
            'title_link'=> url('lead/'.$leadOrm->id)
        ];

//using new slack helper function (release 0.4.3)

        $slackResponse = slack()->post('chat.postMessage', [
            'channel' => '#'.$leadOrm->slack_channel_name,
            'as_user' => true,
            'parse' => 'full',
            'text'=>$slackString,
            'attachments' => json_encode($attachments),
        ]);


        if($requestObject->is_present== true){
            return;
        }

        if(env('APP_ENV')=="production"){

            $channelInfoResponse = SlackChannel::info('C290DP5CP');

            //d($channelInfoResponse);
            $channelMembers = ($channelInfoResponse->channel->members);

            $slackUserIdList = [];
            foreach($channelMembers as $tempSlackUser){
                array_push($slackUserIdList,$tempSlackUser);
            }
            $userList = UserEmployee::whereIn('slack_user_id',$channelMembers)->get();
            $mentionString = "";
            foreach($userList as $tempUser){
                $mentionString .= "@".$tempUser->slack_username." ";
            }

            $slackString = $mentionString." attendance marked as ".($requestObject->is_present?"present":"absent");


            slack()->post('chat.postMessage', [
                'channel' => '#pramati_front_office',
                'as_user' => true,
                'parse' => 'full',
                'text'=>$slackString,
                'attachments' => json_encode($attachments),
            ]);
        }

    }

}