<?php

namespace App\Filament\Resources\AgriTypeResource\Pages;

use App\Filament\Resources\AgriTypeResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAgriType extends ViewRecord
{
    protected static string $resource = AgriTypeResource::class;

//    protected array $initialFormData = [];
//
//    protected function afterFill(): void
//    {
//        $this->initialFormData = $this->snapshotFormState();
//    }


    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action) {
                    if ($this->record->branchCropCompositionCollections()->exists()) {
                        Notification::make()
                            ->title('لا يمكن حذف نوع المحصول')
                            ->body('هذا النوع مستخدم في التركيب المحصولي.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),

            Actions\Action::make('back')
                ->label('عودة')
                ->color('secondary')
                ->icon('heroicon-o-arrow-left')
                ->extraAttributes([
                    'onclick' => 'history.back()',
                ])
        ];
    }
}
