<?php

namespace App\Console\Commands;

use App\Mail\VisitCreated;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RateReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:rate-reminders';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        \Log::info('VisitsSendReminders command started');

        $visits = Visit::all();


        foreach ($visits as $visit) {
//            $startDate = \Carbon\Carbon::parse($visit->start);
            $daysAgo = Setting::first()->reminder_delay_days ?? 2;
            $visitStart = \Carbon\Carbon::parse($visit->start);
            $visitCreated = \Carbon\Carbon::parse($visit->created_at);

            $targetDayStart = $visitStart->copy()->subDays($daysAgo)->startOfDay();
            $targetDayEnd = $visitStart->copy()->subDays($daysAgo)->endOfDay();

            $cutoffTime = $visitCreated->addHours(48);

            \Log::info($targetDayStart . ' -> ' . $targetDayEnd);

                \Log::info("✅ Sending email for visit ID {$visit->id} ({$visit->start})");

                foreach ($visit->emps as $employee) {
                    if ($visit->status == 3 &&  $employee->reviews == null){
                    \Log::info('Sending email to rate reminder to: ' . $employee->user->email);
                    Mail::to($employee->user->email)
                        ->queue(new VisitCreated($visit, null, 'review'));
                    } else {
                        \Log::info("⏩ Skipped visit ID {$visit->id}, not in rate reminder window.");
                }

            }

        }
    }
}
