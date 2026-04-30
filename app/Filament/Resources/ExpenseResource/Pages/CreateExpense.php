<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Models\Expense;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $detailRows = ExpenseResource::extractDetailRows($data);
        $parentData = ExpenseResource::extractParentData($data);
        $parentData['user_id'] = auth()->id();

        return DB::transaction(function () use ($parentData, $detailRows) {
            $record = Expense::create($parentData);

            ExpenseResource::syncChildren($record, $detailRows);

            return $record;
        });
    }
}
