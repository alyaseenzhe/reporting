<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseRecommendationExport implements FromView, WithEvents
{
    use Exportable;

    public $results;
    public $dist_days;
    public $record_type;
    public $show_results;

    public function __construct($results, $dist_days, $record_type, $show_results)
//    public function __construct()
    {
        $this->results = $results;
        $this->dist_days = $dist_days;
        $this->record_type = $record_type;
        $this->show_results = $show_results;
    }

    public function view(): View
    {
        //
        return view('exports.purchaseexport', ['results' => collect($this->results), 'dist_days' => $this->dist_days, 'record_type' => $this->record_type, 'show_results' => $this->show_results]);
//            ->layout('layouts.dashboard');
//        return view('testexport', );
//        return view('exports.testexport', [
//            'invoices' => "AaAa"
//        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }


}
