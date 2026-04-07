<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use App\Models\BranchCropCompositionCollection;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateBranchCropCompositionCollection extends CreateRecord
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

    /**
     * Persist the parent and child rows in one transaction.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $cultivationRows = BranchCropCompositionCollectionResource::extractCultivationRows($data);
        $cropRows = BranchCropCompositionCollectionResource::extractCropRows($data);
        BranchCropCompositionCollectionResource::validateCultivationRowsUnique($cultivationRows);
        $parentData = BranchCropCompositionCollectionResource::extractParentData($data);
        $parentData['user_id'] = auth()->id();
        $parentData['updated_by'] = auth()->id();

        return DB::transaction(function () use ($parentData, $cultivationRows, $cropRows) {
            $record = BranchCropCompositionCollection::create($parentData);

            BranchCropCompositionCollectionResource::syncChildren(
                $record,
                $cultivationRows,
                $cropRows
            );

            return $record;
        });
    }
}
