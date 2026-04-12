<?php

namespace App\Http\Livewire;

use App\Models\UserGroup;
use Livewire\Component;

class CreateUserGroup extends Component
{
    public $name;
    public $cost = 0;
    public $read_type = 1;
    public $write_product_target = 1;
    public $calculate_all_product_target = 0;
    public $choose_special_product = 0;
    public $edit_special_product = 0;

    public $report_type = [];
    public $visits = [];
    public $crops = [];
    public $crop_create_permission;
    public $crop_edit_permission;
    public $crop_delete_permission;
    public $crop_view_permission;
    public $crop_list_permissions = [];

    protected $rules = [
        'name' => 'required',
        'crops' => 'array',
    ];

    protected $messages = [
        'name.required' => 'حقل عنوان المجموعة مطلوب',
    ];

    public function render()
    {
        $cropActionOptions = $this->cropActionOptions();
        $cropListOptions = $this->cropListOptions();

        return view('livewire.create-user-group', compact('cropActionOptions', 'cropListOptions'))
            ->layout('layouts.dashboard');
    }

    public function create() {

//        dd($this->calculate_all_product_target);
        $this->validate();

        $data = \App\Filament\Resources\UserGroupResource::mergeCropPermissionFields([
            'name' => $this->name,
            'report_type' => json_encode($this->report_type),
            'cost' => $this->cost,
            'read_type' => $this->read_type,
            'write_product_target' => $this->write_product_target,
            'calculate_all_product_target' => $this->calculate_all_product_target,
            'choose_special_product' => $this->choose_special_product,
            'edit_special_product' => $this->edit_special_product,
            'visits' => json_encode($this->visits),
            'crop_create_permission' => $this->crop_create_permission,
            'crop_edit_permission' => $this->crop_edit_permission,
            'crop_delete_permission' => $this->crop_delete_permission,
            'crop_view_permission' => $this->crop_view_permission,
            'crop_list_permissions' => $this->crop_list_permissions,
        ]);

        $record = UserGroup::create($data);

        if($record) {
            session()->flash('success', 'تم إنشاء المجموعة بنجاح');
            return redirect()->route('list.groups');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء المجموعة');
            return redirect()->route('list.groups');
        }
    }

    public function cropActionOptions(): array
    {
        return [
            'create' => \App\Filament\Resources\UserGroupResource::getCropActionOptions('create'),
            'edit' => \App\Filament\Resources\UserGroupResource::getCropActionOptions('edit'),
            'delete' => \App\Filament\Resources\UserGroupResource::getCropActionOptions('delete'),
            'view' => \App\Filament\Resources\UserGroupResource::getCropActionOptions('view'),
        ];
    }

    public function cropListOptions(): array
    {
        return \App\Filament\Resources\UserGroupResource::getCropListOptions();
    }
}
