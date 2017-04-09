<?php

namespace App\Console\Commands;

use App\Services\Domain\CaregiverDomainService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class CaregiverAutoAttendance extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caregiver:autoAttendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will schedule calls for CGs to mark attendance';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $caregiverDomainService;

    public function __construct(CaregiverDomainService $caregiverDomainService)
    {
        parent::__construct();
        $this->CaregiverDomainService = $caregiverDomainService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $this->CaregiverDomainService->autocallCaregiver();
    }
}
