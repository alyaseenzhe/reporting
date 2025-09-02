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

    /**
     * This is a Livewire lifecycle hook that runs once when the component is first created.
     * Its purpose is to set up the initial state by finding all employees who report to the
     * currently authenticated user. It queries for users whose 'report_receivers' field contains
     * the current user's employee code, collects their IDs, and stores them for filtering.
     * A copy of the initial list is saved to allow for resetting the filter.
     */
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

    /**
     * This is the standard Livewire method that renders the component's UI. It fetches a distinct list
     * of weekly reports submitted by the employees who report to the current user. The query only runs
     * if there are employees in the `$emp_code` array. The results are ordered to show the newest weeks first.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * This method filters the report list to show reports for only a single selected employee.
     * It's typically triggered by a dropdown or search input in the view. It updates the `$emp_code`
     * property, which automatically causes the `render` method to run again with the new, specific filter.
     */
    public function search() {
//        dd($this->single_emp_code);
        $this->emp_code = [$this->single_emp_code];

    }

    /**
     * This utility method resets the employee filter to its original state. It restores the `$emp_code`
     * property to the full list of all direct reports that was initially loaded in the `mount` method.
     * This is likely connected to a "Clear Filter" or "Show All" button in the view.
     */
    public function resetEmps() {
        $this->emp_code = $this->original_emp_code;
    }
}
