<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EditUser extends Component
{
    public $user_id;
    public $role;
    public $name;
    public $email;
    public $password;

    public $record;


    protected $messages = [
        'name.required' => 'حقل الاسم مطلوب',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.unique' => 'هذا البريد الإلكتروني موجود مسبقاً',
        'password.required' => 'حقل كلمة المرور مطلوب',
        'password.min' => 'كلمة المرور يجب ان يكون على الاقل 8 احرف او ارقام',
    ];

    public function mount($id) {
        try {
            $this->record = User::findOrFail($id);
            $this->user_id = $this->record->id;
            $this->name = $this->record->name;
            $this->email = $this->record->email;
            $this->role = $this->record->role;

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذا المستخدم غير موجود');
            return redirect()->route('list.users');
        }

    }

    public function render()
    {
        return view('livewire.edit-user')
            ->layout('layouts.dashboard');
    }

    public function update() {

        try {
            $record = User::findOrFail($this->user_id);

            if($record->email != $this->email) {
                if ($this->role == "u") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->role = "u";
                    $record->email = $this->email;

                }
                elseif ($this->role == "a") {

                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                        ]);
                    }


                    $record->name = $this->name;
                    $record->role = "a";
                    $record->email = $this->email;
                }
            }
            else {
                if ($this->role == "u") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->role = "u";
                    $record->email = $this->email;

                } elseif ($this->role == "a") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->role = "a";
                    $record->email = $this->email;
                }
            }


            if($record->save()) {
                session()->flash('success', 'تم تحديث بيانات المستخدم بنجاح');
                return redirect()->route('list.users');
            }
            else {
                session()->flash('error-message', 'حدث خطأ ما عند تحديث بيانات المستخدم');
                return redirect()->route('list.users');
            }

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذا المستخدم غير موجود');
            return redirect()->route('list.users');
        }
    }
}
