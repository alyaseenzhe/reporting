<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatUser extends Component
{
    private const ALL_BRANCHES = ['3', '10', '7', '13', '4', '6', '5', '12', '11', '9', '8', '505'];
    private const MRKT_TYPES = ['QryGroup30','QryGroup31','QryGroup32','QryGroup40','QryGroup41', 'QryGroup50', 'QryGroup51', 'QryGroup52', 'QryGroup53'];

    public $emp_code;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $branch_mode = 'selection';
    public $mrkt_mode = 'selection';
//    public $one_branch;
    public $branches = [];
    public $mrkt_types = [];
    public $group_id;

    public $role = 'u';
    public $is_active;

    protected $rules = [
        'emp_code' => 'required',
        'name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required|confirmed|min:8',
        'branch_mode' => 'required|in:all,one,selection',
        'mrkt_mode' => 'required|in:all,selection',
        'branches' => 'required|array|min:1',
        'mrkt_types' => 'required|array|min:1',
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
        $branchOptions = $this->branchOptions();

        return view('livewire.creat-user', compact('groups', 'branchOptions'))
            ->layout('layouts.dashboard');
    }

    public function create() {

//        dd($this->role);
        $this->syncBranchesFromMode();
        $this->syncMrktTypesFromMode();
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
            'mrkt_types' => json_encode($this->mrkt_types),
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

    public function updatedBranchMode(): void
    {
        $this->resetErrorBag('branches');

        if ($this->branch_mode === 'all') {
            $this->branches = self::ALL_BRANCHES;
        }

//        if ($this->branch_mode === 'one') {
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
