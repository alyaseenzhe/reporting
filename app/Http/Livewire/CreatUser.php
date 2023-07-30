<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatUser extends Component
{
    public $emp_code;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $branches = [];
    public $group_id;

    public $role = 'u';

    protected $rules = [
        'emp_code' => 'required',
        'name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required|confirmed|min:8',
        'branches' => 'required|array|min:1',
    ];

    protected $messages = [
        'name.required' => 'حقل الاسم مطلوب',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.unique' => 'هذا البريد الإلكتروني موجود مسبقاً',
        'password.required' => 'حقل كلمة المرور مطلوب',
        'password.confirmed' => 'كلمة المرور غير متطابقتين',
        'password.min' => 'كلمة المرور يجب ان يكون على الاقل 8 احرف او ارقام',
        'branches.required' => 'يجب اختيار فرع واحد على الأقل',
        'branches.array' => 'يجب اختيار فرع واحد على الأقل',
        'branches.min' => 'يجب اختيار فرع واحد على الأقل',
        'emp_code.required' => 'حقل الرقم الوظيفي مطلوب',
    ];

    public function render()
    {
        $groups = UserGroup::all();

        return view('livewire.creat-user', compact('groups'))
            ->layout('layouts.dashboard');
    }

    public function create() {

//        dd($this->role);
        $this->validate();

//        dd($this->branches);

        $record = User::create([
            'emp_code' => $this->emp_code,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'group' => $this->group_id == '-1' ? null : $this->group_id,
            'branches' => json_encode($this->branches),
        ]);

        if($record) {
            session()->flash('success', 'تم إنشاء المستخدم بنجاح');
            return redirect()->route('list.users');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء المستخدم');
            return redirect()->route('list.users');
        }

    }
}
