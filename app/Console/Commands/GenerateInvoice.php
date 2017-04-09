<?php

namespace App\Console\Commands;

use App\Services\Domain\BillingDomainService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class GenerateInvoice extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:invoiceGenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will generate the invoivces automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $billingDomainService;

    public function __construct(BillingDomainService $billingDomainService)
    {
        parent::__construct();
        $this->BillingDomainService = $billingDomainService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $this->BillingDomainService->generateAutoInvoice();
    }
}
