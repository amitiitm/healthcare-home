<?php

namespace App\Console\Commands;

use App\Contracts\Domain\IOperationDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Helper\IMailHelperContract;
use App\Jobs\SendSMSToCustomerOnLeadCreation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;

class SlackUserSyncProcess extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:slacksyncuser';

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

    protected $userDomainService;

    public function __construct(IOperationDomainContract $operationDomainContract, IUserDomainContract $IUserDomainContract)
    {
        parent::__construct();
        $this->operationDomainService = $operationDomainContract;
        $this->userDomainService = $IUserDomainContract;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $slackUsers = SlackUser::all();
        if(isset($slackUsers->members)){
            foreach($slackUsers->members as $tempMember){
                //d($tempMember);
                $this->userDomainService->updateSlackUserInfo($tempMember);
            }
        }



        // userid update


    }
}
