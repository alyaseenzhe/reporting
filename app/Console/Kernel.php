<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        \App\Console\Commands\SendVisitReminders::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('inspire')->hourly();
//        $schedule->command('command:sayhi')->everyTwoMinutes();
//        $schedule->job(new SendMarketingSummaryEmail())->weeklyOn(5, '9:00')->timezone("Asia/Riyadh");
//        $schedule->command('marketing:summary')->weeklyOn(5, '9:00')->timezone("Asia/Riyadh");
//        $schedule->command('marketing:summary')->everyTenMinutes()->timezone("Asia/Riyadh");
        $schedule->command('visits:send-reminders')->dailyAt('9:00');
        $schedule->command('visits:rate-reminders')->cron('0 0 */2 * *');
//        $schedule->command('visits:rate-reminders')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
