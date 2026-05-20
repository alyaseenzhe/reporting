<?php

namespace App\Filament\Resources\AgriTypeResource\Pages;

use App\Filament\Resources\AgriTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgriType extends CreateRecord
{
    protected static string $resource = AgriTypeResource::class;

    protected array $initialFormData = [];

    protected function afterFill(): void
    {
        $this->initialFormData = $this->snapshotFormState();
    }

    protected function getActions(): array
    {
        return array_merge([

            Actions\Action::make('create')
//                ->label(__('filament::resources/pages/create-record.form.actions.create.label'))
                ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
                ->action('create')
                ->keyBindings(['mod+s']),
        ], static::canCreateAnother() ? [
            Actions\Action::make('createAnother')
//                ->label(__('filament::resources/pages/create-record.form.actions.create_another.label'))
                ->label(__('حفظ واضافة سجل جديد'))
                ->action('createAnother')
                ->keyBindings(['mod+shift+s'])
                ->color('secondary'),
            $this->getCancelFormAction(),
            Actions\Action::make('back')
                ->label('عودة')
                ->requiresConfirmation(fn (): bool => $this->hasUnsavedChanges())
//                ->url(static::getResource()::getUrl('index'))
                ->action(function () {
                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
        ] : []);

    }
    protected function getFormActions(): array
    {
        return [];
    }

    protected function hasUnsavedChanges(): bool
    {
        return $this->snapshotFormState() !== $this->initialFormData;
    }

    protected function snapshotFormState(): array
    {
        return $this->normalizeSnapshotValue($this->form->getRawState());
    }

    protected function normalizeSnapshotValue($value)
    {
        if (! is_array($value)) {
            return $value;
        }

        $normalized = [];

        foreach ($value as $key => $item) {
            $normalized[$key] = $this->normalizeSnapshotValue($item);
        }

        return $normalized;
    }
}
