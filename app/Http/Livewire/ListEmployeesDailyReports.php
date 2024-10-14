<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListEmployeesDailyReports extends Component
{
    use WithPagination;

    public $emp_code = [];
    public $single_emp_code = -1;
    public $original_emp_code = [];
    public $emps;

    public $start_date;
    public $end_date;
    public $daily_reports;

    protected $listeners = ['search' => 'search'];

    public function mount() {

        // good
//        $this->emps = User::where('report_receivers', 'LIKE' ,"%".Auth::user()->emp_code."%")
//            ->select('id','name')
//            ->get();

        $this->emps = User::whereNotNull('report_receivers')
            ->orderBy('created_at', 'desc')
            ->select('id','name')
            ->get();

        if ($this->emps->count() > 0) {
            foreach ($this->emps as $emp) {
                array_push($this->emp_code, $emp->id);
            }
        }

        $this->original_emp_code = $this->emp_code;
    }

    public function render()
    {
//        $daily_reports = DailyReport::select('start_of_week', 'end_of_week', 'added_by')
//            ->distinct()
//            ->whereIn("added_by", $this->emp_code)
//            ->where('added_by', "LIKE","%%")
//            ->where('report_date', '>=' ,$this->start_date ? $this->start_date : '2024-01-01')
//            ->where('report_date', '<=' ,$this->end_date ? $this->end_date : Carbon::today()->format('Y-m-d'))
//            ->orderBy('start_of_week', 'DESC')
//            ->orderBy('added_by')
//            ->get();

//            ->paginate(20);

//        return view('livewire.list-employees-daily-reports', compact('daily_reports'))
        return view('livewire.list-employees-daily-reports')
            ->layout('layouts.dashboard');
    }

    public function search($customer_id, $start_date, $end_date) {
//        dd($this->single_emp_code);
//        $this->emp_code = [$this->single_emp_code];
        $this->emp_code = [$customer_id];
//        dd($this->end_date);

        $this->daily_reports = DailyReport::select('start_of_week', 'end_of_week', 'added_by')
            ->distinct()
            ->whereIn("added_by", $this->emp_code)
            ->where('added_by', "LIKE","%%")
            ->where('report_date', '>=' ,$start_date ? $start_date : '2024-01-01')
            ->where('report_date', '<=' ,$end_date ? $end_date : Carbon::today()->format('Y-m-d'))
            ->orderBy('start_of_week', 'DESC')
            ->orderBy('added_by')
            ->get();

        $this->emit('finished');

    }

    public function resetEmps() {
        $this->emp_code = $this->original_emp_code;
    }
}
