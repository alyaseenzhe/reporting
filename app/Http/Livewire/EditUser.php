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
    public $sales_dept_code;
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
        'sales_dept_code'=> 'حقل المكان مطلوب'
    ];

    /**
     * The `mount` method is a Livewire lifecycle hook that is called when the component
     * is first initialized. It's used to pre-populate the component's properties with
     * data from a database record. In this case, it finds a `User` record by its ID and
     * assigns the record's attributes to the public properties of the Livewire component.
     * It also decodes the JSON string for branches. If the user record is not found,
     * it catches the `ModelNotFoundException`, sets a flash message, and redirects the user.
     *
     * @param int $id The ID of the user record to be edited.
     * @return \Illuminate\Http\RedirectResponse|void
     */
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
            $this->sales_dept_code = $this->record->sales_dept_code;

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذا المستخدم غير موجود');
            return redirect()->route('list.users');
        }

    }

    /**
     * The `render` method is the core of a Livewire component. It is responsible for
     * returning the view that will be rendered to the user. This function retrieves
     * all user groups from the database to make them available to the view, then
     * returns the `livewire.edit-user` view and applies the `layouts.dashboard` layout.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $groups = UserGroup::all();

        return view('livewire.edit-user', compact('groups'))
            ->layout('layouts.dashboard');
    }

    /**
     * Updates an existing user's details in the database.
     *
     * This function first finds the user record to be updated by their ID. It then
     * performs a series of conditional validations and updates. The logic is split
     * based on whether the user's email address has been changed and whether a new
     * password has been entered. It handles updates for both 'user' (u) and 'admin' (a)
     * roles, and stores the user's branches as a JSON-encoded string. Finally, it
     * attempts to save the record and redirects the user with a session flash message
     * indicating success or failure. If the user record is not found, it catches
     * the exception and redirects with an error message.
     *
     */
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
                            'sales_dept_code'=>'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'

                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;

                }
                elseif ($this->role == "a") {

                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'

                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'
                        ]);
                    }


                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                }   $record->sales_dept_code = $this->sales_dept_code;
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
                            'sales_dept_code'=>'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;

                } elseif ($this->role == "a") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable'
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;

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
