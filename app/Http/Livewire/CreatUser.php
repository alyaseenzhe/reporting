<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatUser extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public $role = 'u';

    protected $rules = [
        'name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required|confirmed|min:8',
    ];

    protected $messages = [
        'name.required' => 'حقل الاسم مطلوب',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.unique' => 'هذا البريد الإلكتروني موجود مسبقاً',
        'password.required' => 'حقل كلمة المرور مطلوب',
        'password.confirmed' => 'كلمة المرور غير متطابقتين',
        'password.min' => 'كلمة المرور يجب ان يكون على الاقل 8 احرف او ارقام',
    ];

    public function render()
    {
        return view('livewire.creat-user')
            ->layout('layouts.dashboard');
    }

    public function create() {

//        dd($this->role);
        $this->validate();

        $record = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
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
