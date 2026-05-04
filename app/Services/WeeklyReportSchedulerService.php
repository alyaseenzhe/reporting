<?php

namespace App\Services;

use App\Http\Livewire\ListWeeklyReport;
use App\Mail\WeeklyReport;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class WeeklyReportSchedulerService
{
    public function sendAll(string $startDate, string $endDate): void
    {
        foreach (array_keys(config('weekly-report.areas', [])) as $areaId) {
            $this->sendForArea($areaId, $startDate, $endDate);
        }
    }

    public function sendForArea(string $areaId, string $startDate, string $endDate): void
    {
        $areaConfig = config("weekly-report.areas.{$areaId}");

        if (!$areaConfig) {
            throw new InvalidArgumentException("Unsupported weekly report area [{$areaId}].");
        }

        $report = app(ListWeeklyReport::class);
        $report->area_id = $areaId;
        $report->branch_id = $areaConfig['branch_id'];
        $report->start_date = $startDate;
        $report->end_date = $endDate;
        $report->sap_results = [];
        $report->emp_codes = [];
        $report->visits = [];

        $report->proccess_report();

        Mail::to($areaConfig['to'])
            ->cc(config('weekly-report.cc', []))
            ->send(new WeeklyReport(
                $report->sap_results,
                $report->area_id,
                $report->start_date,
                $report->end_date,
                $report->visits
            ));
    }
}
