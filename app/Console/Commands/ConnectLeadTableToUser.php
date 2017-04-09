<?php

namespace App\Console\Commands;

use App\Contracts\Rest\IOperationRestContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConnectLeadTableToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:connectLeadTableToUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Lead Sync Process';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $operationRestService;

    public function __construct(IOperationRestContract $IOperationRestContract){
        parent::__construct();
        $this->operationRestService = $IOperationRestContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->operationRestService->connectLeadTableToUser();
    }
}
