<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Jobs\SendSMSToCustomerOnLeadCreation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class CustomerNotificationCron extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:customernotification';

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

    public function __construct(IOperationDomainContract $operationDomainContract, IMailHelperContract $mailHelperContract)
    {
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
        $this->mailHelperService = $mailHelperContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $serviceStartDate = Carbon::parse('2016-09-23 00:00:00');
        $pendingCGAssignmentNotification = $this->operationDomainService->getPendingCGAssignmentNotification();
        foreach($pendingCGAssignmentNotification as $tempLead){
            $carbonAssignedCG = Carbon::parse($tempLead->cg_assigned_at);
            if($serviceStartDate->gte($carbonAssignedCG)){
                continue;
            }
            if($tempLead->email && trim($tempLead->email)!=""){
                $this->mailHelperService->sendCgAssignedMailNotification($tempLead, true);
                $this->operationDomainService->markCgAssignmentNotificationSend($tempLead->id);
            }
        }


        $pendingQCAssignmentNotification = $this->operationDomainService->getPendingQCAssignmentNotification();
        foreach($pendingQCAssignmentNotification as $tempLead){
            $carbonAssignedCG = Carbon::parse($tempLead->qc_assigned_at);
            if($serviceStartDate->gte($carbonAssignedCG)){
                continue;
            }
            if($tempLead->email && trim($tempLead->email)!=""){
                $this->mailHelperService->sendQCAssignedMailNotification($tempLead, true);
                $this->operationDomainService->markQCAssignmentNotificationSend($tempLead->id);
            }
        }

        $pendingQCAssignmentNotification = $this->operationDomainService->getPendingLeadStartedNotification();
        foreach($pendingQCAssignmentNotification as $tempLead){
            continue;
            $carbonAssignedCG = Carbon::parse($tempLead->started_service_at);
            if($serviceStartDate->gte($carbonAssignedCG)){
                continue;
            }
            if($tempLead->email && trim($tempLead->email)!=""){
                $this->mailHelperService->sendMailToCustomerOnServiceStart($this->operationDomainService->getLeadDetailedOrm($tempLead->id));
                $this->operationDomainService->markLeadStartedNotificationSend($tempLead->id);
            }
        }


    }
}
