<?php

namespace App\Filament\Resources\CropCatalogCategoryResource\Pages;

use App\Filament\Resources\CropCatalogCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCropCatalogCategory extends EditRecord
{
    protected static string $resource = CropCatalogCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
