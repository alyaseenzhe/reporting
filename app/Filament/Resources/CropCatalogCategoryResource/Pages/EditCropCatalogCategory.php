<?php

namespace App\Filament\Resources\CropCatalogCategoryResource\Pages;

use App\Filament\Resources\CropCatalogCategoryResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCropCatalogCategory extends EditRecord
{
    protected static string $resource = CropCatalogCategoryResource::class;


    protected function getActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
                ->action('save')
                ->keyBindings(['mod+s'])
                ->after(function (): void {
                    $this->redirect(static::getResource()::getUrl('view', [
                        'record' => $this->record,
                    ]));
                }),


            $this->getCancelFormAction(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

}
