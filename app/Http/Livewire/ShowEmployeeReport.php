<?php

namespace App\Http\Livewire;

use App\Mail\EmployeeReportMail;
use App\Mail\WeeklyReport;
use App\Models\DailyReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ShowEmployeeReport extends Component
{
    public $records;
    public $user;

    public function mount($id, $week_date) {
        $this->user = User::find($id);
        try {

            $rpt_date = Carbon::parse($week_date);

            $this->records = DailyReport::where('added_by', $id)
                ->where('start_of_week', $week_date)
                ->where('end_of_week', $rpt_date->endOfWeek(Carbon::THURSDAY)->format('Y-m-d'))
                ->orderBy('report_date')
                ->get();

//            dd($this->record);
//            $this->record = DailyReport::findOrFail($id);


//            if ($this->record->added_by != Auth::id()) {
//                session()->flash('message', 'هذا التقرير غير موجود');
//                return redirect()->route('list.employees-daily-reports');
//            }

        } catch (ModelNotFoundException $exception) {

            session()->flash('message', 'هذا التقرير غير موجود');
            return redirect()->route('list.employees-daily-reports');
        }

    }

    public function render()
    {
        return view('livewire.show-employee-report')
            ->layout('layouts.dashboard');
    }

    public function sendReport() {


        $codes = explode(',', $this->user->report_receiver);

        $emails = User::whereIn('emp_code', $codes)
            ->pluck('email')
            ->toArray();
//        dd($this->user->report_receivers);

//        Mail::bcc($emails)->send(new ReportMail($report));

//        Mail::to(['basil.alrashed@alyaseenagri.com'])->queue(new WeeklyReport($this->emp_total, $this->area_id, $this->start_date, $this->end_date, $this->emp_codes, $this->customer_purchased, $this->visits, $this->category_qty, $this->category_qty_total));
         Mail::to(['mohammedsr@alyaseenagri.com'])->bcc(['basil.alrashed@alyaseenagri.com'])->queue(new EmployeeReportMail($this->records));
//        Mail::to($emails)->bcc( $emails)->queue(new EmployeeReportMail($this->records));

//        Mail::to(['basil.alrashed@alyaseenagri.com'])->bcc(['basil.alrashed@alyaseenagri.com'])->queue(new EmployeeReportMail($this->records));

        return 0;
    }
}
