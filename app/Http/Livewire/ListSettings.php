<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class ListSettings extends Component
{
    public $dist_days;
    public $item_price;

    protected $rules = [
        'dist_days' => 'required|numeric|min:0',
        'item_price' => 'required',
    ];

    protected $messages = [
        'dist_days.required' => 'مطلوب',
        'dist_days.min' => 'الفترة المسموح بها 0 او اكثر',
        'item_price.required' => 'مطلوب',
    ];

    /**
     * This is a Livewire lifecycle hook that runs once when the component is initialized.
     * It is responsible for loading the existing application settings from the database and
     * populating the public properties, which in turn fills the form fields with the current values.
     */
    public function mount() {
        $record = Setting::first();
        $this->dist_days = $record->dist_days;
        $this->item_price = $record->item_price;
    }

    /**
     * The standard Livewire method that renders the component's Blade view and sets the master
     * dashboard layout for a consistent UI.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.list-settings')
            ->layout('layouts.dashboard');
    }

    /**
     * This action method is responsible for saving the updated settings to the database. It is
     * triggered when the user submits the form. It first validates the user's input, then updates
     * the settings record, and finally provides feedback to the user with a success or error message
     * before redirecting them back to the same page.
     */
    public function save() {

        $this->validate();

        $first = Setting::first();
        $record = $first->update(['dist_days' => $this->dist_days, 'item_price' => $this->item_price]);

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
