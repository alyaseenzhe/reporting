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

    // Listens for a 'search' event emitted from the frontend
    protected $listeners = ['search' => 'search'];

    /**
     * This is a Livewire lifecycle hook that runs once when the component is first initialized.
     * It's responsible for populating the initial list of employees whose reports can be viewed.
     * It fetches all users who have report receivers defined, stores their IDs for filtering,
     * and keeps a copy of the original list so the filter can be reset.
     */
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

    /**
     * This is the standard Livewire method that renders the component's Blade view. The actual data fetching
     * for the reports is handled by the `search()` method, so this function's main role is to render the
     * initial page structure and filters.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * This is the primary action method for fetching and filtering the report data. It's triggered by a
     * 'search' event from the frontend, taking an employee ID and a date range as parameters. It then
     * queries the database for a distinct list of weekly report groups submitted by that specific employee
     * within the given dates.
     *
     * @param int $customer_id The ID of the employee to search for.
     * @param string $start_date The start of the date range filter.
     * @param string $end_date The end of the date range filter.
     */
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

        // Notify the frontend that the search process is complete
        $this->emit('finished');

    }

    /**
     * This is a utility method used to reset the employee filter. It restores the list of employees
     * to be searched back to the initial, complete list that was loaded when the component was first
     * mounted. This is typically used for a "Clear Filter" or "Show All" button.
     */
    public function resetEmps() {
        $this->emp_code = $this->original_emp_code;
    }
}
