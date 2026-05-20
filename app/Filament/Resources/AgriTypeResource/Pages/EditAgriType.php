<?php

namespace App\Filament\Resources\AgriTypeResource\Pages;

use App\Filament\Resources\AgriTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
class EditAgriType extends EditRecord
{
    protected static string $resource = AgriTypeResource::class;

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

            //Actions\DeleteAction::make(),
//            Actions\DeleteAction::make()
//                ->before(function (Actions\DeleteAction $action) {
//                    if ($this->record->branchCropCompositionCollections()->exists()) {
//                        Notification::make()
//                            ->title('لا يمكن حذف نوع المحصول')
//                            ->body('هذا النوع مستخدم في التركيب المحصولي.')
//                            ->danger()
//                            ->send();
//
//                        $action->cancel();
//                    }
//                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
//    protected function getActions(): array
//    {
//        return [
//            Actions\DeleteAction::make(),
//        ];
//    }
}
