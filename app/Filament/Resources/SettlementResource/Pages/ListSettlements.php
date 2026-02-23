<?php

namespace App\Filament\Resources\SettlementResource\Pages;

use App\Filament\Resources\SettlementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettlements extends ListRecords
{
    protected static string $resource = SettlementResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
