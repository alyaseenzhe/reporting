<?php

namespace App\Filament\Resources\CropCatalogCategoryResource\Pages;

use App\Filament\Resources\CropCatalogCategoryResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCropCatalogCategory extends ViewRecord
{
    protected static string $resource = CropCatalogCategoryResource::class;

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
