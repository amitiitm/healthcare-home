<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Contracts\Helper\ISlackHelperContract;
use App\Jobs\SendSMSToCustomerOnLeadCreation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;

class SlackNotificationProcess extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:slacknotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $operationDomainService;

    protected $slackHelperService;

    protected $userDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract, IUserDomainContract $IUserDomainContract, ISlackHelperContract $ISlackHelperContract)
    {
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
        $this->userDomainService = $IUserDomainContract;
        $this->slackHelperService = $ISlackHelperContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(env('SLACK_NOTIFICATION')!='enabled'){
            return;
        }
        $currentTimeStamp = Carbon::now();
        Log::info("LeadCallNotificationAndConnect.php Job ");

        $timeStartTrackingDate = Carbon::now();
        $timeStartTrackingDate->subDay(env('SLACK_BACK_DATE_LEAD'));
//        $timeStartTrackingDate->subMinute(10);

        $timeStartTrackingDate->hour = 0;
        $timeStartTrackingDate->minute = 0;
        $timeStartTrackingDate->second = 0;

        $timeEndForSendingDate = Carbon::now();

        // getting all lead which is not approved and created within 2 days


        $allLeads = $this->operationDomainService->getLeadCreatedInDuration($timeStartTrackingDate,$timeEndForSendingDate);

        foreach($allLeads as $lead){
            //Log::info("Lead for Slack: ".$lead->id);
            $slackChannelName = null;
            if($lead->slack_channel_created_at == null){
                $slackChannelName = $this->operationDomainService->checkAndGenerateSlackChannelForLead($lead->id);
            }else if($lead->slack_channel_id!=null && $lead->slack_channel_name!="" && $lead->slack_channel_id=='1'){
                $carbonNow = Carbon::now();
                Log::info("-----------------");
                Log::info($lead->id);
                $slackChannelName = $this->operationDomainService->checkAndGenerateSlackChannelForLead($lead->id);
                Log::info($slackChannelName);
            }

            $dateNow = Carbon::now();
            $leadCreatedDate = Carbon::parse($lead->created_at);
            echo $durationInDays = $dateNow->diffInDays($leadCreatedDate);



            if($slackChannelName!=null && $slackChannelName!=""){
                $channelCreated = SlackChannel::create($slackChannelName);
                Log::info(json_encode($channelCreated));



                if($channelCreated->ok==true && $channelCreated->channel){
                    $this->operationDomainService->updateSlackChannelIdForLead($lead->id,$channelCreated->channel->id);
                    SlackChannel::setTopic($channelCreated->channel->id,env('APP_URL')."lead/".$lead->id);
                    $this->operationDomainService->addWatchersToLead($lead->id,$this->userDomainService->getUserIdByEmailList([
                        'seema.chauhan@pramaticare.com',
                        'robin@pramaticare.com',
                        'richa@pramaticare.com',
                        'kajal@pramaticare.com',
                        'vishal@pramaticare.com',
                        'harshit@pramaticare.com',
                        'sachin@pramaticare.com'
                    ]));
                    $this->slackHelperService->newLeadNotification($lead);

                }
            }
        }

        // only for local
        $allLeads = $this->operationDomainService->getLeadList();

        foreach($allLeads as $tempLead){
            if($tempLead->slack_channel_id == "" || $tempLead->slack_channel_id=='1'){
                return;
            }
            SlackChannel::setTopic($tempLead->slack_channel_id,url("lead/".$tempLead->id));
//            SlackChannel::archive($tempLead->slack_channel_id);

        }

        $allSlackChannels = SlackChannel::all(true);


        //Log::info(json_encode($allSlackChannels));
        $slackChannelMapper = [];
        if(isset($allSlackChannels->channels) && $allSlackChannels->channels){
            foreach($allSlackChannels->channels as $tempSlack){
                $slackChannelMapper[$tempSlack->name]=$tempSlack->id;
            }
        }
        $allSlackedLead = $this->operationDomainService->getAllSlackedLead();
        foreach($allSlackedLead as $tempLead){
            //Log::info("Tracking Lead for Slack ID: ".$tempLead->id);
            if($tempLead->slack_id!=""){
                continue;
            }
            if($tempLead->slack_channel_name=="" && $tempLead->slack_channel_name!=1){
                continue;
            }
            if(isset($slackChannelMapper[strtolower($tempLead->slack_channel_name)])){
                $this->operationDomainService->updateSlackChannelIdForLead($tempLead->id,$slackChannelMapper[strtolower($tempLead->slack_channel_name)]);
            }

        }


        /// add stakeholders to lead
        $timeStartTrackingDate = Carbon::now();
        //$timeStartTrackingDate->subDay(1);
        $timeStartTrackingDate->subDays(3);

        $timeStartTrackingDate->hour = 0;
        $timeStartTrackingDate->minute = 0;
        $timeStartTrackingDate->second = 0;

        $timeEndForSendingDate = Carbon::now();
        $allLeads = $this->operationDomainService->getLeadCreatedInDuration($timeStartTrackingDate,$timeEndForSendingDate);
        foreach($allLeads as $tempLead){
            $watcherList = [];

           // Log::info('Employee Assigned');
            foreach($tempLead->employeesAssigned as $tempUser){
                array_push($watcherList,$tempUser->id);
            }
           // Log::info('QC Assigned');
            foreach($tempLead->qcAssigned as $tempUser){
                array_push($watcherList,$tempUser->id);
            }
            //Log::info('fieldAssigned Assigned');
            foreach($tempLead->fieldAssigned as $tempUser){
                array_push($watcherList,$tempUser->id);
            }
            $this->operationDomainService->addWatchersToLead($tempLead->id,$watcherList);
        }

        $pendingWatchersInvitations = $this->operationDomainService->getPendingWatcherInvitationForSlack();
        foreach($pendingWatchersInvitations as $tempWatcher){
            Log::info("Processing Invitation for watcher: ".$tempWatcher->id);
            if(!$tempWatcher->user->employeeInfo){
                Log::info('returning from $tempWatcher->user->employeeInfo check');
                continue;
            }
            $employeeInfo = $tempWatcher->user->employeeInfo;

            if($tempLead->slack_channel_id==""){
                Log::info("Returning .....");
                //continue;
            }
            if(!$tempWatcher->slack_invitation_send_at){
                $this->operationDomainService->markAndSendSlackInvitationForWatcher($tempWatcher->id);
            }else{
                Log::info('returning from $employeeInfo->slack_invitation_send_at check');
            }


        }





        // userid update


    }
}
