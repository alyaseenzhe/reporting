<?php

namespace App\Filament\Resources\EmployeeSettlementResource\Pages;

use App\Filament\Resources\EmployeeSettlementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeSettlement extends EditRecord
{
    protected static string $resource = EmployeeSettlementResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
