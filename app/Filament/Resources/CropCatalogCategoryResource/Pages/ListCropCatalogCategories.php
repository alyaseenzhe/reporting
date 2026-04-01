<?php

namespace App\Filament\Resources\CropCatalogCategoryResource\Pages;

use App\Filament\Resources\CropCatalogCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCropCatalogCategories extends ListRecords
{
    protected static string $resource = CropCatalogCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
