<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListBranchCropCompositionCollections extends ListRecords
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return function (Model $record): ?string {
            if (static::getResource()::canEdit($record)) {
                return static::getResource()::getUrl('edit', ['record' => $record]);
            }

            if (static::getResource()::canView($record)) {
                return static::getResource()::getUrl('view', ['record' => $record]);
            }

            return null;
        };
    }
}
