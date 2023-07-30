<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;

class EditUserGroups extends Component
{
    public $group_id;
    public $record;

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

    public function mount($id) {
        try {

            $this->record = UserGroup::findOrFail($id);

            $this->group_id = $this->record->id;
            $this->name = $this->record->name;
            $this->report_type = json_decode($this->record->report_type);
            $this->cost = $this->record->cost;
            $this->read_type = $this->record->read_type;

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذه المجموعة غير موجودة');
            return redirect()->route('list.groups');
        }

    }

    public function render()
    {
        return view('livewire.edit-user-groups')
            ->layout('layouts.dashboard');
    }

    public function update() {

        try {

            $record = UserGroup::findOrFail($this->group_id);

            $this->validate();

            $record->name = $this->name;
            $record->report_type = json_encode($this->report_type);
            $record->cost = $this->cost;
            $record->read_type = $this->read_type;


            if($record->save()) {
                session()->flash('success', 'تم تحديث المجموعة بنجاح');
                return redirect()->route('list.groups');
            }
            else {
                session()->flash('error-message', 'حدث خطأ ما عند تحديث المجموعة');
                return redirect()->route('list.groups');
            }

        }
        catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذه المجموعة غير موجودة');
            return redirect()->route('list.groups');
        }
    }
}
