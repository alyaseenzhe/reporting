<?php

namespace App\Console\Commands;

use App\Services\WeeklyReportDateRange;
use App\Services\WeeklyReportSchedulerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendWeeklyReport extends Command
{
    protected $signature = 'report:send-weekly {--date= : Sending Friday date/time used to calculate the report range}';

    protected $description = 'Send the weekly report email for all branches';

    public function handle(WeeklyReportDateRange $dateRange, WeeklyReportSchedulerService $weeklyReportScheduler): int
    {
        $timezone = config('weekly-report.timezone', config('app.timezone'));
        $sendingAt = $this->option('date')
            ? Carbon::parse($this->option('date'), $timezone)
            : Carbon::now($timezone);

        $range = $dateRange->forSendingFriday($sendingAt);

        $this->info(sprintf(
            'Sending weekly reports for %s to %s (sending time %s).',
            $range['start_date'],
            $range['end_date'],
            $sendingAt->format('Y-m-d H:i:s')
        ));

        $weeklyReportScheduler->sendAll($range['start_date'], $range['end_date']);

        $this->info('Weekly reports sent successfully.');

        return self::SUCCESS;
    }
}
