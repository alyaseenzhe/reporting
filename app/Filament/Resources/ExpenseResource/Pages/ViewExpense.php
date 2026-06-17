<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
class ViewExpense extends ViewRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return ExpenseResource::mutateDataBeforeFill($data, $this->record);
    }

    protected function getActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
//                ->icon('heroicon-o-document-download')
                ->action(function () {
                    $pdf = Pdf::loadView('pdf.expenses', [
                        'record' => $this->record,
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'expense-' . $this->record->id . '.pdf'
                    );
                }),

            Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->extraAttributes([
                    'onclick' => 'window.print(); return false;',
                ]),
        ];
    }
}
