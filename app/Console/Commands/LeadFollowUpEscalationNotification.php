<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LeadFollowUpEscalationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:escalation';

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
        $currentTimeStamp = Carbon::now();

        if($currentTimeStamp->hour>20 || $currentTimeStamp->hour<9 || $currentTimeStamp->dayOfWeek == Carbon::SUNDAY){
            return;
        }

        $timeStartTrackingDate = Carbon::now();
        //$timeStartTrackingDate->subDay(2);
        $timeStartTrackingDate->hour = 0;
        $timeStartTrackingDate->minute = 0;
        $timeStartTrackingDate->second = 0;

        $timeEndForSendingDate = Carbon::now();
        $timeEndForSendingDate->subMinutes(15);


        $allLeads = $this->operationDomainService->getLeadCreatedInDuration($timeStartTrackingDate,$timeEndForSendingDate);
        foreach($allLeads as $tempLeads){
            if($tempLeads->statuses->count()==0 && $tempLeads->approvalEscalations->count()==0){
                $this->mailHelperService->sendLeadApprovalEscalationMail($tempLeads);
                $this->operationDomainService->markLeadApprovalEscalation($tempLeads->id,15);
                return;
            }
        }

    }
}
