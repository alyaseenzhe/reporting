<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Livewire\Component;
use Livewire\WithPagination;

class ListUserGroups extends Component
{
    use WithPagination;

    /**
     * This is the standard Livewire method that renders the component's view. It is responsible for
     * fetching and displaying a paginated list of all user groups in the system. The groups are ordered by
     * their creation date to show the most recently created ones first.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $groups = UserGroup::orderBy('created_at', 'DESC')->paginate(20);

        return view('livewire.list-user-groups', compact('groups'))
            ->layout('layouts.dashboard');
    }

    /**
     * This action method is responsible for deleting a specific user group from the database. It is
     * typically triggered by a "delete" button in the group list. It takes the group's unique ID,
     * attempts to delete the record, and then provides feedback to the administrator with a
     * success or error message before redirecting back to the group list.
     *
     * @param int $id The ID of the UserGroup record to be deleted.
     */
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
