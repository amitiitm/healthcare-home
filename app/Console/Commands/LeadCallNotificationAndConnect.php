<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LeadCallNotificationAndConnect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:callandconnect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will notify about the pending calls and call the customer';

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
        Log::info("LeadCallNotificationAndConnect.php Job ");

        if($currentTimeStamp->hour>=21 || $currentTimeStamp->hour<8 || $currentTimeStamp->dayOfWeek == Carbon::SUNDAY){
            return;
        }

//        Log::info('Call and connect');
        $timeStartTrackingDate = Carbon::now();
        //$timeStartTrackingDate->subDay(2);
        $timeStartTrackingDate->subDay(2);

        $timeStartTrackingDate->hour = 0;
        $timeStartTrackingDate->minute = 0;
        $timeStartTrackingDate->second = 0;


        $timeEndForSendingDate = Carbon::now();
        $timeEndForSendingDate->subMinutes(15);


        // getting all lead which is not approved and created within 2 days




        $allLeads = $this->operationDomainService->getLeadCreatedInDuration($timeStartTrackingDate,$timeEndForSendingDate);
        $callInProgress = false;
        foreach($allLeads as $tempLeads){
            if($tempLeads->statuses->count()==0){
                if($tempLeads->call_initiation_mail_send_at==null){
                    $this->mailHelperService->sendLeadIncomingCallMail($tempLeads);
                    $this->operationDomainService->markCallInitiationMailSend($tempLeads);
                }
            }
            if($tempLeads->call_initiated_at!=null){
                $carbonNow = Carbon::now();
                $diffInMin = $carbonNow->diffInMinutes(Carbon::parse($tempLeads->call_initiated_at));
                if($diffInMin<5){
                    $callInProgress=true;
                }
            }
        }



        foreach($allLeads as $tempLeads){
            if($tempLeads->statuses->count()==0 && $tempLeads->call_initiated_at==null  && $tempLeads->call_initiation_mail_send_at!=null && !$callInProgress){
                $carbonNow = Carbon::now();
                $carbonCallMailTime = Carbon::parse(($tempLeads->call_initiation_mail_send_at));
                Log::info($tempLeads->call_initiation_mail_send_at);
                if($carbonNow->diffInMinutes($carbonCallMailTime)>5){
                    // generate call
                    Log::info("Call generating for ".$tempLeads->id." Phone:".$tempLeads->phone);
                    $this->operationDomainService->automatedCallLead($tempLeads->id,env('MYOPERATOR_SUPPORT_USERID'));
                    return;
                }
            }
        }


        $timeStartTrackingDate = Carbon::now();
        //$timeStartTrackingDate->subDay(2);
        $timeStartTrackingDate->subDay(1);

        $timeStartTrackingDate->hour = 0;
        $timeStartTrackingDate->minute = 0;
        $timeStartTrackingDate->second = 0;


        $timeEndForSendingDate = Carbon::now();
        $timeEndForSendingDate->subMinutes(1);


        // getting all lead which is not approved and created within 2 days




        $allLeads = $this->operationDomainService->getLeadCreatedInDuration($timeStartTrackingDate,$timeEndForSendingDate);



        foreach($allLeads as $tempLead){
            //

            if($tempLead->employeesAssigned->count()==0){
                continue;
            }
            if($tempLead->primaryVendorsAssigned->count()==0){
                continue;
            }
            if($tempLead->qcAssigned->count()==0){
                continue;
            }
            if($tempLead->fieldAssigned->count()==0){
                continue;
            }
            // all assignment done


            if($tempLead->customer_assignment_mail_send_at==null){
                Log::info("All assignment done sending mail notification for ".$tempLead->id);
                $this->mailHelperService->sendMailToCustomerAboutFieldAssignment($tempLead);
                $tempLead->customer_assignment_mail_send_at = Carbon::now();
                $tempLead->save();
            }
            if($tempLead->customer_assignment_sms_send_at==null){
                Log::info("All assignment done sending SMS notification for ".$tempLead->id);
                $this->operationDomainService->sendSmsToCustomerAboutFieldAssignment($tempLead);
                $tempLead->customer_assignment_sms_send_at = Carbon::now();
                $tempLead->save();
            }
        }




        return;

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
