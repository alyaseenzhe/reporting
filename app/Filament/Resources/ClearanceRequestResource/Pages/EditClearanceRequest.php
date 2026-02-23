<?php

namespace App\Filament\Resources\ClearanceRequestResource\Pages;

use App\Filament\Resources\ClearanceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClearanceRequest extends EditRecord
{
    protected static string $resource = ClearanceRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
