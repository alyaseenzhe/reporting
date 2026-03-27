<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditBranchCropCompositionCollection extends EditRecord
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    /**
     * Populate repeaters from the saved child rows.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return BranchCropCompositionCollectionResource::mutateDataBeforeFill($data, $this->record);
    }

    /**
     * Persist the updated parent and child rows in one transaction.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $cultivationRows = BranchCropCompositionCollectionResource::extractCultivationRows($data);
        $cropRows = BranchCropCompositionCollectionResource::extractCropRows($data);
        $parentData = BranchCropCompositionCollectionResource::extractParentData($data);

        return DB::transaction(function () use ($record, $parentData, $cultivationRows, $cropRows) {
            $record->update($parentData);

            BranchCropCompositionCollectionResource::syncChildren(
                $record,
                $cultivationRows,
                $cropRows
            );

            return $record;
        });
    }

    /**
     * Define the page actions.
     */
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
