<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowDailyReport extends Component
{
    public $record;
    public function mount($id) {
        try {
            $this->record = DailyReport::findOrFail($id);


            if ($this->record->added_by != Auth::id()) {
                session()->flash('message', 'هذا التقرير غير موجود');
                return redirect()->route('list.daily-reports');
            }

        } catch (ModelNotFoundException $exception) {

            session()->flash('message', 'هذا التقرير غير موجود');
            return redirect()->route('list.daily-reports');
        }

    }

    public function render()
    {
        return view('livewire.show-daily-report')
            ->layout('layouts.dashboard');
    }
}
