<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Models\ORM\LeadStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VendorRatingSyncProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:vendorratingsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vendor Rating Process';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $operationDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract){
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->operationDomainService->vendor_rating_save();
    }
}
