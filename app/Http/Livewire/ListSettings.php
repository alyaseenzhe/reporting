<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class ListSettings extends Component
{
    public $dist_days;

    protected $rules = [
        'dist_days' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'dist_days.required' => 'مطلوب',
        'dist_days.min' => 'الفترة المسموح بها 0 او اكثر',
    ];


    public function mount() {
        $record = Setting::first();
        $this->dist_days = $record->dist_days;
    }
    public function render()
    {
        return view('livewire.list-settings')
            ->layout('layouts.dashboard');
    }

    public function save() {

        $this->validate();

        $first = Setting::first();
        $record = $first->update(['dist_days' => $this->dist_days]);

        if($record) {
            session()->flash('success', 'تم تحديث إعدادات الموقع بنجاح');
            return redirect()->route('list.settings');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند تحديث إعدادات الموقع');
            return redirect()->route('list.settings');
        }
    }
}
