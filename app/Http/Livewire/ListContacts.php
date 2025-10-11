<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ListContacts extends Component
{
    use WithPagination;

    public function render()
    {
//        dd(json_decode(Auth::user()->user_group->report_type));
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(20);

        return view('livewire.list-contacts', compact('contacts'))
            ->layout('layouts.dashboard');
    }


    public function show(Contact $contact)
    {
////        dd(json_decode(Auth::user()->user_group->report_type));
//        $contacts = Contact::find();

        return view('livewire.show-contact', compact('contact'));
    }
    public function delete($id) {
        $record = Contact::destroy($id);
        if($record) {
            session()->flash('success', 'تم حذف هذا المستخدم بنجاح');
            return redirect()->route('list.contacts');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند حذف هذا المستخدم');
            return redirect()->route('list.contacts');
        }
    }
}
