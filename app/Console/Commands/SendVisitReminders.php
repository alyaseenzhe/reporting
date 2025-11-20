<?php

namespace App\Console\Commands;

use App\Mail\VisitCreated;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVisitReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'command:name';
    protected $signature = 'visits:send-reminders';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for visits created a week ago';

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

        $visits = Visit::all(); // مؤقتاً نجيب الكل للتجربة


        foreach ($visits as $visit) {
//            $startDate = \Carbon\Carbon::parse($visit->start);
            $daysAgo = Setting::first()->reminder_delay_days ?? 2; // أو أي رقم افتراضي
            $visitStart = \Carbon\Carbon::parse($visit->start);
            $visitCreated = \Carbon\Carbon::parse($visit->created_at);

            $targetDayStart = $visitStart->copy()->subDays( $daysAgo)->startOfDay();
            $targetDayEnd   = $visitStart->copy()->subDays( $daysAgo)->endOfDay();

            $cutoffTime = $visitCreated->addHours(48);

            \Log::info($targetDayStart.' -> '.$targetDayEnd);
            if ($visit->status == 1 && now()->between($targetDayStart, $targetDayEnd)) {
                \Log::info("✅ Sending email for visit ID {$visit->id} ({$visit->start})");

                foreach ($visit->emps as $employee) {
                    \Log::info('Sending email to: ' . $employee->user->email);
                    Mail::to($employee->user->email)
                        ->queue(new VisitCreated($visit, null, 'reminder'));
                }
            } else {
                \Log::info("⏩ Skipped visit ID {$visit->id}, not in reminder window.");
            }

            if($visit->status == 0 && $visit->created_at >= $cutoffTime){
                \Log::info("✅ Sending email to Accept reminder for visit ID {$visit->id} ({$visit->start})");
                foreach ($visit->emps as $employee) {
                    Mail::to($employee->user->email)
                        ->queue(new VisitCreated($visit, null, 'AcceptReminder'));
            }

            }
            else {
                \Log::info("⏩ Skipped visit ID {$visit->id}, not in AcceptReminder window.{$cutoffTime}");
            }
        }

        \Log::info('VisitsSendReminders command finished');



//        \Log::info('VisitsSendReminders command started');
//
//        $visits = Visit::all();
//
//        foreach ($visits as $visit) {
//        // Get number of days from column
//        $daysAgo = $visit->days_before_email; // or whatever your column name is
//
//        // Compare with current date
//   //     $targetDate = $visit->start->subDays($daysAgo)->startOfDay();
////        $visits = Visit::whereDate('start', now()->subWeek())->get();
////        $visits = Visit::whereBetween('start', [now()->subDays($daysAgo)->startOfDay();, now()->subWeek()->endOfDay()])->get();
////            if (now()->isSameDay(7)) {
//            if (now()->between($visit->start->subDays(7)->startOfDay(),$visit->start->subDays(7)->endOfDay())) {
//                \Log::info('Condition is true: ' . $visit->start->subDays(7)->startOfDay());
//            foreach ($visit->emps as $employee) {
//                \Log::info('Sending email to: ' . $employee->user->email);
//                Mail::to($employee->user->email)->queue(new VisitCreated($visit, NULL, 'add'));
//            }
//            }
//        }
//        \Log::info('VisitsSendReminders command finished');
    }
}
