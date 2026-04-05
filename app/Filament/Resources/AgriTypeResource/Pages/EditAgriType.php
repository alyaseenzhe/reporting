<?php

namespace App\Filament\Resources\AgriTypeResource\Pages;

use App\Filament\Resources\AgriTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgriType extends EditRecord
{
    protected static string $resource = AgriTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
