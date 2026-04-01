<?php

namespace App\Filament\Resources\CropCatalogItemResource\Pages;

use App\Filament\Resources\CropCatalogItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCropCatalogItems extends ListRecords
{
    protected static string $resource = CropCatalogItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
