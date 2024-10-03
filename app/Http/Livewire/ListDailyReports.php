<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListDailyReports extends Component
{
    use WithPagination;

    public function render()
    {
        $daily_reports = DailyReport::where('added_by', Auth::id())
            ->orderBy('report_date', 'DESC')->paginate(20);

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
}
