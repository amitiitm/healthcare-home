<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Jobs\SendAttendanceSMSJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use App\Models\DTO\Reports\ActiveProjectGridItemDTO;

class SendAttendanceSMSShift2 extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:sendattendancesmsshift2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to CG and Customer For Attendance for shift 2';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $operationDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract)
    {
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $activeLeads = $this->operationDomainService->getActiveLeads();

        $activeProjectDto = new ActiveProjectGridItemDTO();
        foreach($activeLeads as $tempLead){
            $dto = $activeProjectDto->convertToDTO($tempLead);
            
            // for 12 hr night
            $this->dispatch(new SendAttendanceSMSJob($dto->cgPhone,2));
            //$this->dispatch(new SendAttendanceSMSJob($dto->phone,3));
        }

    }
}
