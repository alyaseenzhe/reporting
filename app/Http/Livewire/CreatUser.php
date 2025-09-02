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
    public $is_active;

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

    /**
     * Renders the view for creating a new user.
     *
     * This function retrieves all user groups from the database to make them
     * available to the view. It then returns the 'livewire.creat-user' view,
     * which is rendered using the 'layouts.dashboard' layout.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $groups = UserGroup::all();

        return view('livewire.creat-user', compact('groups'))
            ->layout('layouts.dashboard');
    }

    /**
     * Creates a new user after validating the input data.
     *
     * This method first validates the data submitted from the form. It then
     * creates a new User record in the database, hashing the password before
     * storing it. The user's group is set to `null` if no group is selected.
     * The selected branches are encoded into a JSON string. Finally, it
     * redirects the user to the user list with a success or error message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'is_active' => $this->is_active,
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
