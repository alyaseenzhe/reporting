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

    protected array $initialFormData = [];

    protected function afterFill(): void
    {
        $this->initialFormData = $this->snapshotFormState();
    }

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
        BranchCropCompositionCollectionResource::validateCultivationRowsUnique($cultivationRows);
        BranchCropCompositionCollectionResource::validateCropRowsUnique($cropRows);

        return DB::transaction(function () use ($record, $data, $cultivationRows, $cropRows) {
            $parentData = BranchCropCompositionCollectionResource::prepareParentData($data);
            $parentData['updated_by'] = auth()->id();

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
            $this->getCancelFormActionWithConfirmation(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return Actions\Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->color('secondary')
            ->visible(fn (): bool => ! $this->hasUnsavedChanges())
            ->action(function (): void {
                $this->redirect($this->previousUrl ?? static::getResource()::getUrl());
            });
    }

    protected function getCancelFormActionWithConfirmation(): Actions\Action
    {
        return Actions\Action::make('cancelWithConfirmation')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->color('secondary')
            ->requiresConfirmation()
            ->visible(fn (): bool => $this->hasUnsavedChanges())
            ->modalHeading(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->modalButton(__('filament-support::actions/modal.actions.confirm.label'))
            ->action(function (): void {
                $this->redirect($this->previousUrl ?? static::getResource()::getUrl());
            });
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
