<?php

namespace App\Filament\Resources\RequestResource\Pages;

use App\Filament\Resources\RequestResource;
use App\Models\Settlement;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;

    protected function afterCreate(): void
    {
        $request = $this->record; // الطلب الذي تم إنشاؤه

        $types = Settlement::all();

        foreach ($types as $type) {
            $request->settlements()->attach([
                'settlement_id' => $type->id,
                'is_done' => false,
            ]);
        }
    }

}
