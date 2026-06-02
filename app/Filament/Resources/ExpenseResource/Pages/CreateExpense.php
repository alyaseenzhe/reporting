<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $detailRows = ExpenseResource::extractDetailRows($data);
        $parentData = ExpenseResource::extractParentData($data);
        $parentData['user_id'] = auth()->id();

        return DB::transaction(function () use ($parentData, $detailRows) {
            $record = Expense::create($parentData);

            ExpenseResource::syncChildren($record, $detailRows);
            $record->load('user');

            DB::afterCommit(function () use ($record): void {
                $employee = $record->user;

                if (! $employee || blank($employee->manager_id)) {
                    return;
                }

                $manager = User::where('emp_code', $employee->manager_id)->first();

                if (! $manager) {
                    return;
                }

                try {
                    Notification::make()
                        ->title('New expense created')
                        ->body(sprintf('%s created expense #%s.', $employee->name, $record->id))
                        ->icon('heroicon-o-currency-dollar')
                        ->actions([
                            NotificationAction::make('viewExpense')
                                ->label('View expense')
                                ->url(ExpenseResource::getUrl('view', ['record' => $record])),
                        ])
                        ->sendToDatabase($manager, true);
                } catch (Throwable $exception) {
                    report($exception);
                }
            });

            return $record;
        });
    }
}
