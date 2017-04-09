<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Jobs\SendSMSToCustomerOnLeadCreation;
use App\Models\ORM\LeadStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;

class ActiveProjectSyncProcess extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:activeprojectsync';

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

    protected $mailHelperService;

    protected $userDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract, IUserDomainContract $IUserDomainContract)
    {
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
        $this->userDomainService = $IUserDomainContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $leadList = $this->operationDomainService->getStartedLead();

        $leadMapper = array();


        foreach($leadList as $tempLead){
            if(!isset($leadMapper[$tempLead->lead_id])){
                $leadMapper[$tempLead->lead_id] = array();
                $leadMapper[$tempLead->lead_id]['started_date'] = $tempLead->created_at;
                $leadMapper[$tempLead->lead_id]['status_changed'] = false;
            }else{
                // echo "yes".$tempLead->lead_id;
            }
        }

        $startedLeadStatus = $this->operationDomainService->getStatusBySlug('started');

        foreach($leadMapper as $leadId=>$mappedData){
            $startedDate = Carbon::parse($mappedData['started_date']);
            $startedDate->hour=0;
            $startedDate->minute=0;
            $startedDate->second=0;
            $leadStatusChanged = LeadStatus::whereRaw('lead_id = ? and created_at > ?', array($leadId,$startedDate))->get();
            if(count($leadStatusChanged)==0){
                // no status changed

                // save into table
            }
            $leadDateStatusMapper = array();
            foreach($leadStatusChanged as $tempLeadStatus){
                $carbonStartedDate = Carbon::parse($tempLeadStatus->created_at);
                $leadDateStatusMapper[$carbonStartedDate->toDateString()] = $tempLeadStatus->status_id;
            }
            // TODO: optimize code with time consideration
            $startedDateCopied = $startedDate->copy();

            $leadStarted = true;
            for(;;){
                if($startedDateCopied->gt(Carbon::now())){
                    break;
                }
                if(!isset($leadDateStatusMapper[$startedDateCopied->toDateString()]) && $leadStarted){
                    // lead is active on the date
                    $this->operationDomainService->checkAndMarkActiveDate($leadId,$startedDateCopied->toDateString());
                }else if( isset($leadDateStatusMapper[$startedDateCopied->toDateString()]) && $leadDateStatusMapper[$startedDateCopied->toDateString()]== $startedLeadStatus->id){
                    //lead is active on date
                    $this->operationDomainService->checkAndMarkActiveDate($leadId,$startedDateCopied->toDateString());
                    $leadStarted = true;
                }else{
                    $leadStarted = false;
                }
                $startedDateCopied->addDay();
            }

        }

    }
}
