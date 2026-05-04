<?php

namespace App\Exports;

use App\Models\DailyReport;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DailyReport::where('added_by', 124)->get();
    }
}
