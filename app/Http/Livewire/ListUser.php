<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListUser extends Component
{
    // This trait enables pagination functionality for the component.
    use WithPagination;

    /**
     * This is the standard Livewire method that renders the component's view. It is responsible for
     * fetching and displaying a paginated list of all users in the system. The users are ordered by
     * their creation date to show the most recently registered users first.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
//        dd(json_decode(Auth::user()->user_group->report_type));
        $users = User::orderBy('created_at', 'DESC')->paginate(20);

        return view('livewire.list-user', compact('users'))
            ->layout('layouts.dashboard');
    }

    /**
     * This action method is responsible for deleting a specific user from the database. It is
     * typically triggered by a "delete" button in the user list. It takes the user's unique ID,
     * attempts to delete the record, and then provides feedback to the administrator with a
     * success or error message before redirecting back to the user list.
     *
     * @param int $id The ID of the User record to be deleted.
     */
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
