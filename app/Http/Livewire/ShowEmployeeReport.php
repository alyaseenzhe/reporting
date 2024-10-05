<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowEmployeeReport extends Component
{
    public $records;

    public function mount($id, $week_date) {

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
}
