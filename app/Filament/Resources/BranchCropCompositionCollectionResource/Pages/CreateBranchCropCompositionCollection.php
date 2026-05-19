<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Pages;

use App\Filament\Resources\BranchCropCompositionCollectionResource;
use App\Models\BranchCropCompositionCollection;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateBranchCropCompositionCollection extends CreateRecord
{
    protected static string $resource = BranchCropCompositionCollectionResource::class;

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

    protected function getCancelFormAction(): Actions\Action
    {
        return Actions\Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->color('secondary')
            ->requiresConfirmation(fn (): bool => $this->hasUnsavedChanges())
            ->modalHeading(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->modalButton(__('filament-support::actions/modal.actions.confirm.label'))
            ->action(function (): void {
                $this->redirect(static::getResource()::getUrl('create'));
            });
    }
    /**
     * Persist the parent and child rows in one transaction.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $cultivationRows = BranchCropCompositionCollectionResource::extractCultivationRows($data);
        $cropRows = BranchCropCompositionCollectionResource::extractCropRows($data);
        BranchCropCompositionCollectionResource::validateCultivationRowsUnique($cultivationRows);
        BranchCropCompositionCollectionResource::validateCropRowsUnique($cropRows);
        return DB::transaction(function () use ($data, $cultivationRows, $cropRows) {
            $parentData = BranchCropCompositionCollectionResource::prepareParentData($data);
            $parentData['user_id'] = auth()->id();
            $parentData['created_by'] = auth()->id();
            $parentData['updated_by'] = auth()->id();

            $record = BranchCropCompositionCollection::create($parentData);

            BranchCropCompositionCollectionResource::syncChildren(
                $record,
                $cultivationRows,
                $cropRows
            );

            return $record;
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
