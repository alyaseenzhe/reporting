<?php

namespace App\Filament\Resources\AgriDetailsResource\Pages;

use App\Filament\Resources\AgriDetailsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgriDetails extends EditRecord
{
    protected static string $resource = AgriDetailsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
