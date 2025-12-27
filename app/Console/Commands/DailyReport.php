<?php

namespace App\Console\Commands;

use App\Mail\EmployeeReportMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:daily-report';

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
        $start_of_week = now()->startOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        \Log::info($start_of_week);

        //get the employees who's the ceo asked for their reports, they will be sent each week
        // I added my code 10344 in report receivers column to filter the employees

        $employees = \App\Models\DailyReport::where('start_of_week', now()->startOfWeek(Carbon::FRIDAY))
            ->whereHas('user', function ($q) {
                $q->whereRaw("FIND_IN_SET(?, report_receivers)", ['10344']);
            })
            ->distinct()
            ->orderBy('added_by')
            ->pluck('added_by');

        \Log::info($employees);

        foreach ($employees as $employeeId) {

            $reports = \App\Models\DailyReport::where('added_by', $employeeId)
                ->whereDate(
                    'start_of_week',
                    now()->startOfWeek(Carbon::FRIDAY)
                )
                ->get();

            if ($reports->isEmpty()) {
                continue;
            }

            Mail::to(['mohammedsr@alyaseenagri.com'])->bcc(['zahra@alyaseenagri.com'])->queue(new EmployeeReportMail($reports));


        }

        //todo this loop isn't working should be fix

        $employeeIds = ['10334', '10224', '10200', '10335'];

        $employeesR = \App\Models\DailyReport::where('start_of_week', now()->startOfWeek(Carbon::FRIDAY))
            ->whereIn('added_by', $employeeIds)
            ->distinct()
            ->orderBy('added_by')
            ->pluck('added_by');
        \Log::info($employeesR);

        foreach ( $employeesR as $employeeId ) {

            // Get employee email
            $employee = \App\Models\User::where('emp_code', $employeeId)->first();
            \Log::info($employee);

            if (!$employee || !$employee->email) {
                continue;
            }

            $friends_reports = \App\Models\DailyReport::whereDate(
                'start_of_week',
                now()->startOfWeek(Carbon::FRIDAY)
            )
//                ->whereIn('added_by', $employeeIds)
                ->orderBy('added_by')
                ->get();
            \Log::info($friends_reports);

            if ($friends_reports->isEmpty()) {
                continue;
            }
            Mail::to($employee->email)
                ->queue(new EmployeeReportMail($friends_reports));
        }


    }
}
