<?php

namespace App\Filament\Resources\AgriTypeResource\Pages;

use App\Filament\Resources\AgriTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgriTypes extends ListRecords
{
    protected static string $resource = AgriTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, -1];
    }
}
