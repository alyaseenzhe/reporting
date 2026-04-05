<?php

namespace App\Filament\Resources\AgriDetailsResource\Pages;

use App\Filament\Resources\AgriDetailsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgriDetails extends ListRecords
{
    protected static string $resource = AgriDetailsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
