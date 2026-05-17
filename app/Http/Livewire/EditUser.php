<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EditUser extends Component
{
    private const ALL_BRANCHES = ['3', '10', '7', '13', '4', '6', '5', '12', '11', '9', '8', '505'];
    private const MRKT_TYPES = ['QryGroup30','QryGroup31','QryGroup32','QryGroup40','QryGroup41', 'QryGroup50', 'QryGroup51', 'QryGroup52', 'QryGroup53'];

    public $user_id;
    public $role;
    public $is_active;
    public $name;
    public $emp_code;
    public $email;
    public $password;
    public $branch_mode = 'selection';
    public $mrkt_mode = 'selection';
//    public $one_branch;
    public $branches = [];
    public $mrkt_types = [];
    public $group_id;
    public $sales_dept_code;
    public $record;
    public $users;
    public $manager_id;


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
        'mrkt_types.required' => 'يجب اختيار قسم واحد على الأقل',
        'mrkt_types.array' => 'يجب اختيار قسم واحد على الأقل',
        'mrkt_types.min' => 'يجب اختيار قسم واحد على الأقل',
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
            $this->branches = array_values(array_map('strval', json_decode($this->record->branches, true) ?? []));
            $this->mrkt_types = array_values(array_map('strval', json_decode($this->record->mrkt_types, true) ?? []));
            $this->setBranchModeFromBranches();
            $this->setMrktModeFromTypes();
            $this->sales_dept_code = $this->record->sales_dept_code;
            $this->manager_id = $this->record->manager_id;
            $this->users = User::where('is_active', 1)->get();

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
        $branchOptions = $this->branchOptions();

        return view('livewire.edit-user', compact('groups', 'branchOptions'))
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
            $this->syncBranchesFromMode();
            $this->syncMrktTypesFromMode();
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
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'

                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->mrkt_types = json_encode($this->mrkt_types);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;
                    $record->manager_id = $this->manager_id;

                }
                elseif ($this->role == "a") {

                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'

                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required|unique:users',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                    }


                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->mrkt_types = json_encode($this->mrkt_types);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                }   $record->sales_dept_code = $this->sales_dept_code;
                $record->manager_id = $this->manager_id;
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
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "u";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->mrkt_types = json_encode($this->mrkt_types);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;
                    $record->manager_id = $this->manager_id;

                } elseif ($this->role == "a") {
                    if (!empty($this->password)) {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes|min:8',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                        $record->password = Hash::make($this->password);
                    } else {
                        $this->validate([
                            'name' => 'required',
                            'emp_code' => 'required',
                            'email' => 'required',
                            'password' => 'sometimes',
                            'branches' => 'required|array|min:1',
                            'mrkt_types' => 'required|array|min:1',
                            'sales_dept_code'=>'nullable',
                            'manager_id'=> 'nullable'
                        ]);
                    }

                    $record->name = $this->name;
                    $record->emp_code = $this->emp_code;
                    $record->role = "a";
                    $record->email = $this->email;
                    $record->is_active = $this->is_active;
                    $record->branches = json_encode($this->branches);
                    $record->mrkt_types = json_encode($this->mrkt_types);
                    $record->group = $this->group_id == '-1' ? null : $this->group_id;
                    $record->sales_dept_code = $this->sales_dept_code;
                    $record->manager_id = $this->manager_id;

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

    public function updatedBranchMode(): void
    {
        $this->resetErrorBag('branches');

        if ($this->branch_mode === 'all') {
            $this->branches = self::ALL_BRANCHES;
        }

//        if ($this->branch_mode === 'one') {
//            $this->one_branch = $this->one_branch ?: ($this->branches[0] ?? null);
//            $this->branches = filled($this->one_branch) ? [(string) $this->one_branch] : [];
//        }
    }

    public function updatedMrktMode(): void
    {
        $this->resetErrorBag('mrkt_types');

        if ($this->mrkt_mode === 'all') {
            $this->mrkt_types = self::MRKT_TYPES;
        }
    }

//    public function updatedOneBranch(): void
//    {
//        if ($this->branch_mode === 'one') {
//            $this->branches = filled($this->one_branch) ? [(string) $this->one_branch] : [];
//        }
//    }

    public function branchOptions(): array
    {
        return [
            '3' => 'الأحساء',
            '10' => 'جدة',
            '7' => 'الرياض',
            '13' => 'وادي الدواسر',
            '4' => 'الجوف',
            '6' => 'الدمام',
            '5' => 'الخرج',
            '12' => 'نجران',
            '11' => 'حايل',
            '9' => 'تبوك',
            '8' => 'القصيم',
            '505' => 'ساجر',
        ];
    }

    protected function setBranchModeFromBranches(): void
    {
        $currentBranches = array_values(array_unique(array_map('strval', $this->branches ?? [])));
        $allBranches = self::ALL_BRANCHES;
        sort($currentBranches);
        sort($allBranches);

        if ($currentBranches === $allBranches) {
            $this->branch_mode = 'all';
//            $this->one_branch = null;

            return;
        }

//        if (count($currentBranches) === 1) {
//            $this->branch_mode = 'one';
//            $this->one_branch = $currentBranches[0];
//
//            return;
//        }

        $this->branch_mode = 'selection';
//        $this->one_branch = null;
    }

    protected function setMrktModeFromTypes(): void
    {
        $currentTypes = array_values(array_unique(array_map('strval', $this->mrkt_types ?? [])));
        $allTypes = self::MRKT_TYPES;
        sort($currentTypes);
        sort($allTypes);

        $this->mrkt_mode = $currentTypes === $allTypes ? 'all' : 'selection';
    }

    protected function syncBranchesFromMode(): void
    {
        if ($this->branch_mode === 'all') {
            $this->branches = self::ALL_BRANCHES;

            return;
        }

//        if ($this->branch_mode === 'one') {
//            $this->branches = filled($this->one_branch) ? [(string) $this->one_branch] : [];
//
//            return;
//        }

        $this->branches = array_values(array_unique(array_map('strval', $this->branches ?? [])));
    }

    protected function syncMrktTypesFromMode(): void
    {
        if ($this->mrkt_mode === 'all') {
            $this->mrkt_types = self::MRKT_TYPES;

            return;
        }

        $this->mrkt_types = array_values(array_unique(array_filter(
            array_map('strval', $this->mrkt_types ?? [])
        )));
    }
}
