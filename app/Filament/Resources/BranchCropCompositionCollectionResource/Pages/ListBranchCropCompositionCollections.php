<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListBranchCropCompositionCollections extends ListRecords
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    protected int $defaultTableRecordsPerPageSelectOption = 25;

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, -1];
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return function (Model $record): ?string {
            if (static::getResource()::canView($record)) {
                return static::getResource()::getUrl('view', ['record' => $record]);
            }

            return null;
        };
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('report')
                ->label('تقرير التركيب المحصولي')
                ->icon('heroicon-o-chart-bar')
                ->url(static::getResource()::getUrl('report')),
        ];
    }
}
