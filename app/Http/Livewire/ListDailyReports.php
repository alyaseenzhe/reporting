<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListDailyReports extends Component
{
    use WithPagination; // Enables easy pagination for the report list

    /**
     * This is the standard Livewire method that renders the component's view. It is responsible for
     * fetching and displaying a paginated list of the daily reports that were created by the currently
     * authenticated user. The reports are ordered by their date in descending order to show the most
     * recent ones first.
     *
     * @return \Illuminate\View\View
     */

    public function render()
    {
        $daily_reports = DailyReport::where('added_by', Auth::id())
            ->orderBy('report_date', 'DESC')->paginate(20);

        return view('livewire.list-daily-reports', compact('daily_reports'))
            ->layout('layouts.dashboard');
    }

    /**
     * This action method is responsible for deleting a specific daily report record. It is typically
     * called when a user clicks a "delete" button next to a report in the list. It takes the unique
     * ID of the report, destroys the corresponding record in the database, and then provides feedback
     * to the user with a success or error message before redirecting them back to the reports list.
     *
     * @param int $id The ID of the DailyReport record to be deleted.
     */
    public function delete($id) {
        $record = DailyReport::destroy($id);
        if($record) {
            session()->flash('success', 'تم حذف هذا التقرير بنجاح');
            return redirect()->route('list.daily-reports');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند حذف هذا التقرير');
            return redirect()->route('list.daily-reports');
        }
    }
}
