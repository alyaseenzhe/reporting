<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListEmployeesDailyReports extends Component
{
    use WithPagination;

    public function render()
    {
        $daily_reports = DailyReport::select('start_of_week', 'end_of_week', 'added_by')
            ->distinct()
            ->orderBy('start_of_week', 'DESC')
            ->orderBy('added_by')
            ->paginate(20);

        return view('livewire.list-employees-daily-reports', compact('daily_reports'))
            ->layout('layouts.dashboard');
    }
}
