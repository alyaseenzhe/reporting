<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EditUser extends Component
{
    public $user_id;
    public $role;
    public $is_active;
    public $name;
    public $emp_code;
    public $email;
    public $password;
    public $branches = [];
    public $group_id;

    public $record;


    protected $messages = [
        'emp_code.required' => 'حقل الرقم الوظيفي مطلوب',
        'name.required' => 'حقل الاسم مطلوب',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.unique' => 'هذا البريد الإلكتروني موجود مسبقاً',
        'password.required' => 'حقل كلمة المرور مطلوب',
        'password.min' => 'كلمة المرور يجب ان يكون على الاقل 8 احرف او ارقام',
        'branches.required' => 'يجب اختيار فرع واحد على الأقل',
        'branches.array' => 'يجب اختيار فرع واحد على الأقل',
        'branches.min' => 'يجب اختيار فرع واحد على الأقل',
    ];

    public function mount($id) {
        try {
            $this->record = User::findOrFail($id);
            $this->user_id = $this->record->id;
            $this->emp_code = $this->record->emp_code;
            $this->name = $this->record->name;
            $this->email = $this->record->email;
            $this->role = $this->record->role;
            $this->group_id = $this->record->group;
            $this->is_active = $this->record->is_active;
            $this->branches = json_decode($this->record->branches);

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذا المستخدم غير موجود');
            return redirect()->route('list.users');
        }

    }

    public function render()
    {
        $groups = UserGroup::all();

        return view('livewire.edit-user', compact('groups'))
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
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;

                }
                elseif ($this->role == "a") {

                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                        ]);
                    }


                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                }
            }
            else {
                if ($this->role == "u") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;

                } elseif ($this->role == "a") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
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
