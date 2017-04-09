<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Jobs\SendSMSToCustomerOnLeadCreation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class SendNewLeadNotification extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:newLeadNotification';

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
        //Log::info('SendNewLeadNotification: '.Carbon::now());
        $timeStartTrackingDate = Carbon::now();
        $timeStartTrackingDate->subDay(2);

        $timeEndForSendingDate = Carbon::now();
        $timeEndForSendingDate->subMinutes(10);
        Log::info(json_encode($timeStartTrackingDate));
        $allLeads = $this->operationDomainService->getNotNotifiedNewLeadFromGivenDate($timeStartTrackingDate);
        foreach($allLeads as $tempLeads){





            if(!$tempLeads->notification_sent && ($tempLeads->submission_complete) ){
                // send notification

                $this->mailHelperService->sendWelcomeMailToCustomer($tempLeads);
                $this->mailHelperService->sendNewLeadCreationEmail($tempLeads);
                $this->mailHelperService->sendMailForEmployeeAssignment($tempLeads);
                $this->dispatch(new SendSMSToCustomerOnLeadCreation($this->operationDomainService,$tempLeads));
                $this->operationDomainService->markNewLeadNotificationSend($tempLeads->id);
                continue;
            }
            if(!$tempLeads->notification_sent && (!$tempLeads->submission_complete) && $tempLeads->updated_at < $timeEndForSendingDate ){
                $this->mailHelperService->sendWelcomeMailToCustomer($tempLeads);
                $this->mailHelperService->sendNewLeadCreationEmail($tempLeads);
                $this->mailHelperService->sendMailForEmployeeAssignment($tempLeads);
                $this->dispatch(new SendSMSToCustomerOnLeadCreation($this->operationDomainService,$tempLeads));
                $this->operationDomainService->markNewLeadNotificationSend($tempLeads->id);
                continue;
            }

        }
    }
}
