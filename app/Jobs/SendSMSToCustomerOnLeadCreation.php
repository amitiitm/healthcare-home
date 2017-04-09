<?php

namespace App\Jobs;

use App\Contracts\Domain\IOperationDomainContract;
use App\Jobs\Job;
use App\Models\ORM\Lead;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendSMSToCustomerOnLeadCreation extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $lead;

    protected $operationDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract, Lead $lead)
    {
        $this->lead = $lead;
        $this->operationDomainService = $operationDomainContract;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("SMS Sending in job to ".$this->lead->phone);

        $messageForCustomer =urlencode(str_replace("|:name:|",($this->lead->customer_name." ".$this->lead->customer_last_name),env('SMS_STR_NEW_LEAD_CUSTOMER')));
        $this->operationDomainService->sendSMS($this->lead->phone,$messageForCustomer);
    }
}
