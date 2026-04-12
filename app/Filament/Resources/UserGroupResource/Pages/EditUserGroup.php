<?php

namespace App\Filament\Resources\UserGroupResource\Pages;

use App\Filament\Resources\UserGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserGroup extends EditRecord
{
    protected static string $resource = UserGroupResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return UserGroupResource::fillCropPermissionFields($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return UserGroupResource::mergeCropPermissionFields($data);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
