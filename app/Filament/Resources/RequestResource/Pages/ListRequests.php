<?php

namespace App\Filament\Resources\RequestResource\Pages;

use App\Filament\Resources\RequestResource;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ListRequests extends ListRecords
{
    protected static string $resource = RequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('requestNewRequest')
                ->label('ارسال اشعار لطلب جديد')
                ->icon('heroicon-o-bell')
                ->modalHeading('ارسال اشعار لطلب جديد')
                ->modalButton('ارسال الاشعار')
                ->form([
                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->placeholder('اختر المستخدم')
                        ->options(fn (): array => $this->getEligibleRequestRecipientsQuery()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray())
                        ->searchable()
                        ->required()
                        ->helperText('اختر المستخدم الذي ترغب منه انشاء طلب اخلاء طرف.'),
                ])
                ->action(fn (array $data) => $this->sendCreateRequestNotification($data)),
            Actions\CreateAction::make(),
        ];
    }

    protected function sendCreateRequestNotification(array $data): void
    {
        $recipient = $this->getEligibleRequestRecipientsQuery()
            ->whereKey($data['user_id'] ?? null)
            ->first();

        if (! $recipient) {
            Notification::make()
                ->title('لم يتم إرسال الإشعار')
                ->body('يرجى اختيار مستخدم مفعل يمكنه إنشاء الطلبات.')
                ->danger()
                ->send();

            return;
        }

        try {
            Notification::make()
                ->title('يرجى إنشاء طلب جديد')
                ->body(sprintf('%s تم طلب إنشاء طلب اخلاء طرف منك.', Auth::user()->name ?? 'المستخدم'))
                ->icon('heroicon-o-document-add')
                ->actions([
                    NotificationAction::make('createRequest')
                        ->label('Create request')
                        ->url(RequestResource::getUrl('create'))
                        ->button(),
                ])
                ->sendToDatabase($recipient, true);

            Notification::make()
                ->title('تم إرسال الإشعار')
                ->body(sprintf('تم إرسال الإشعار إلى %s.', $recipient->name))
                ->success()
                ->send();
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('لم يتم إرسال الإشعار')
                ->body('حدث خطأ أثناء إرسال الإشعار. يرجى المحاولة مرة أخرى.')
                ->danger()
                ->send();
        }
    }

    protected function getEligibleRequestRecipientsQuery(): Builder
    {
        return User::query()
            ->where('id', '!=', Auth::id())
//            ->where('role', 'a')
            ->where(function (Builder $query): void {
                $query
                    ->where('is_active', true)
                    ->orWhereNull('is_active');
            });
    }
}
