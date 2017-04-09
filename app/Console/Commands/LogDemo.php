<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LogDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command wil add log file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        /*$user = User::where('email','=',"mohit2007gupta@gmail.com");
        Log::info('I was here at: '.Carbon::now());
        Mail::send('emails.temp', ['user' => $user], function ($m) use ($user) {
            $m->from(env('PHPMAILER_FROM_EMAIL'), 'Cron mail');
            Log::info($user);
            $m->to($user->email, $user->name)->subject("Cron Mail every five min Application");
        });*/
    }
}
