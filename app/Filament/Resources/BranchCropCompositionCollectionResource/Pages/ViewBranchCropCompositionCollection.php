<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBranchCropCompositionCollection extends ViewRecord
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return BranchCropCompositionCollectionResource::mutateDataBeforeFill($data, $this->record);
    }

    protected function getActions(): array
    {
        return [];
    }
}
