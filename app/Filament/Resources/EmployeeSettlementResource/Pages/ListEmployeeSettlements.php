<?php

namespace App\Filament\Resources\EmployeeSettlementResource\Pages;

use App\Filament\Resources\EmployeeSettlementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeSettlements extends ListRecords
{
    protected static string $resource = EmployeeSettlementResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
