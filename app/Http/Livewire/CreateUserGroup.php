<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Livewire\Component;

class CreateUserGroup extends Component
{
    public $name;
    public $cost = 0;
    public $read_type = 1;
    public $write_product_target = 1;
    public $calculate_all_product_target = 0;
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

//        dd($this->calculate_all_product_target);
        $this->validate();

        $record = UserGroup::create([
            'name' => $this->name,
            'report_type' => json_encode($this->report_type),
            'cost' => $this->cost,
            'read_type' => $this->read_type,
            'write_product_target' => $this->write_product_target,
            'calculate_all_product_target' => $this->calculate_all_product_target,
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
