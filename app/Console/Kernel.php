<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\LogDemo::class,
        \App\Console\Commands\SendNewLeadNotification::class,
        \App\Console\Commands\LeadFollowUpEscalationNotification::class,
        \App\Console\Commands\LeadCallNotificationAndConnect::class,
        \App\Console\Commands\SlackNotificationProcess::class,
        \App\Console\Commands\SlackUserSyncProcess::class,
        \App\Console\Commands\CustomerNotificationCron::class,
        \App\Console\Commands\GenerateInvoice::class,
        \App\Console\Commands\CaregiverAutoAttendance::class,
        \App\Console\Commands\CaregiverAutoAttendanceResponse::class,
        \App\Console\Commands\ActiveProjectSyncProcess::class,
        \App\Console\Commands\SendAttendanceSMSShift1::class,
        \App\Console\Commands\SendAttendanceSMSShift2::class,
        \App\Console\Commands\SendAttendanceSMSShift3::class,
        \App\Console\Commands\VendorRatingSyncProcess::class,
        \App\Console\Commands\ConnectLeadTableToUser::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */    
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work')->cron('* * * * * *');
  		/*$schedule->command('inspire')->hourly();*/
		/*$schedule->command('log:demo')->everyMinute();*/
       // $schedule->command('lead:newLeadNotification')->cron('* * * * * *');
       // $schedule->command('lead:callandconnect')->cron('* * * * * *');
       // $schedule->command('lead:escalation')->cron('* * * * * *');
       // $schedule->command('lead:slacknotification')->cron('* * * * * *');
       // $schedule->command('lead:slacksyncuser')->cron('* * * * * *');
        $schedule->command('lead:customernotification')->cron('* * * * * *');
        $schedule->command('lead:activeprojectsync')->twiceDaily(4,18);
        $schedule->command('billing:invoiceGenerate')->dailyAt('13:00');
       // $schedule->command('lead:activeprojectsync')->hourly();
        $schedule->command('lead:sendattendancesmsshift1')->dailyAt('07:00');
        $schedule->command('lead:sendattendancesmsshift2')->dailyAt('19:00');
        $schedule->command('lead:sendattendancesmsshift3')->dailyAt('10:00');
        $schedule->command('caregiver:autoAttendance')->dailyAt('07:30');
        $schedule->command('caregiver:autoAttendance')->dailyAt('10:00');
        $schedule->command('caregiver:autoAttendance')->dailyAt('18:30');
        $schedule->command('caregiver:autoAttendanceResponse')->dailyAt('08:00');
        $schedule->command('caregiver:autoAttendanceResponse')->dailyAt('10:30');
        $schedule->command('caregiver:autoAttendanceResponse')->dailyAt('19:00');
        $schedule->command('vendor:vendorratingsync')->daily();
        $schedule->command('lead:connectLeadTableToUser')->everyFiveMinutes();

    }

}
