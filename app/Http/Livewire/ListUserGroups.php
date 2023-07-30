<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Livewire\Component;
use Livewire\WithPagination;

class ListUserGroups extends Component
{
    use WithPagination;
    public function render()
    {
        $groups = UserGroup::orderBy('created_at', 'DESC')->paginate(20);

        return view('livewire.list-user-groups', compact('groups'))
            ->layout('layouts.dashboard');
    }

    public function delete($id) {
        $record = UserGroup::destroy($id);
        if($record) {
            session()->flash('success', 'تم حذف هذه المجموعة بنجاح');
            return redirect()->route('list.groups');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند حذف هذه المجموعة');
            return redirect()->route('list.groups');
        }
    }
}
