<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewDailyReport extends Component
{
    public $report_note;
    public $location1 = "المركز الرئيسي - الاحساء";
    public $location2;
    public $report_date;
    public $report_type = 'work';
    public $customer_name;
    public $companion;

    protected $rules = [
        'location1' => 'required',
        'location2' => 'required',
        'companion' => 'required',
        'report_date' => 'required',
        'report_note' => 'required',
        'customer_name' => 'required|not_in:-1',
    ];

    protected $messages = [
        'location1.required' => 'مطلوب',
        'location2.required' => 'مطلوب',
        'companion.required' => 'مطلوب',
        'report_date.required' => 'مطلوب',
        'report_note.required' => 'مطلوب',
        'customer_name.required' => 'مطلوب',
        'customer_name.not_in' => 'مطلوب',
    ];

    public function render()
    {
        $customers = AccMast::where('Type', '10')
            ->select('Code', 'Arabic_Name')
            ->get();

        return view('livewire.new-daily-report', compact('customers'))
            ->layout('layouts.dashboard');
    }

    public function generateReport() {

        $this->validate();

        $record = DailyReport::create([
            'location1' => $this->location1,
            'report_date' => $this->report_date,
            'report_type' => $this->report_type,
            'location2' => $this->location2,
            'customer_name' => $this->customer_name,
            'companion' => $this->companion,
            'report_note' => $this->report_note,
            'added_by' => Auth::id(),
        ]);

        if($record) {
            session()->flash('success', 'تم إنشاء التقرير بنجاح');
            return redirect()->route('list.daily-reports');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء التقرير');
            return redirect()->route('list.daily-reports');
        }
    }
}
