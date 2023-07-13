<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListUser extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(20);

        return view('livewire.list-user', compact('users'))
            ->layout('layouts.dashboard');
    }

    public function delete($id) {
        $record = User::destroy($id);
        if($record) {
            session()->flash('success', 'تم حذف هذا المستخدم بنجاح');
            return redirect()->route('list.users');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند حذف هذا المستخدم');
            return redirect()->route('list.users');
        }
    }
}
