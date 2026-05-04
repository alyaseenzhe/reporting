<?php

namespace App\Http\Livewire;

use App\Exports\DailyReportExport;
use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
class ListDailyReports extends Component
{
    use WithPagination;

    public function render()
    {
        $daily_reports = DailyReport::where('added_by', Auth::id())
            ->orderBy('report_date', 'DESC')->paginate(5);

//        dd(implode(", ", $this->emp_code));
//        dd($friends_reports);

        return view('livewire.list-daily-reports', compact('daily_reports'))
            ->layout('layouts.dashboard');
    }

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

    public function export()
    {
        return Excel::download(new DailyReportExport(), 'dailyReport.xlsx');
    }
}
