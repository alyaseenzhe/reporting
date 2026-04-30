<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return ExpenseResource::mutateDataBeforeFill($data, $this->record);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $detailRows = ExpenseResource::extractDetailRows($data);
        $parentData = ExpenseResource::extractParentData($data);

        return DB::transaction(function () use ($record, $parentData, $detailRows) {
            $record->update($parentData);

            ExpenseResource::syncChildren($record, $detailRows);

            return $record;
        });
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
