<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListFriendsDailyReports extends Component
{
    use WithPagination;

    public $emp_code = [];
    public $single_emp_code = -1;
    public $original_emp_code = [];
    public $emps;

    public function mount() {

        $this->emps = User::where('report_receivers', 'LIKE' ,"%".Auth::user()->emp_code."%")
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
        $friends_reports = [];
        if (count($this->emp_code) > 0) {
            $friends_reports = DailyReport::select('start_of_week', 'end_of_week', 'added_by')
                ->distinct()
                ->whereIn("added_by", $this->emp_code)
                ->where('added_by', "LIKE","%%")
                ->orderBy('start_of_week', 'DESC')
                ->orderBy('added_by')
                ->get();
//                ->paginate(20);
        }

        return view('livewire.list-friends-daily-reports', compact('friends_reports'))
            ->layout('layouts.dashboard');
    }

    public function search() {
//        dd($this->single_emp_code);
        $this->emp_code = [$this->single_emp_code];

    }

    public function resetEmps() {
        $this->emp_code = $this->original_emp_code;
    }
}
