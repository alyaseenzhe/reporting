<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Livewire\Component;

class CreateUserGroup extends Component
{
    public $name;
    public $cost = 0;
    public $read_type = 1;
    public $report_type = [];

    protected $rules = [
        'name' => 'required',
    ];

    protected $messages = [
        'name.required' => 'حقل عنوان المجموعة مطلوب',
    ];

    public function render()
    {
        return view('livewire.create-user-group')
            ->layout('layouts.dashboard');
    }

    public function create() {

        $this->validate();

        $record = UserGroup::create([
            'name' => $this->name,
            'report_type' => json_encode($this->report_type),
            'cost' => $this->cost,
            'read_type' => $this->read_type,
        ]);

        if($record) {
            session()->flash('success', 'تم إنشاء المجموعة بنجاح');
            return redirect()->route('list.groups');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء المجموعة');
            return redirect()->route('list.groups');
        }
    }
}
