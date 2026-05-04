<?php

namespace App\Services;

use Carbon\Carbon;

class WeeklyReportDateRange
{
    public function forSendingFriday(Carbon $sendingAt): array
    {
        $timezone = $sendingAt->getTimezone();
        $sendingFriday = $sendingAt->copy()->setTimezone($timezone)->startOfDay();
        $endDate = $sendingFriday->copy()->subDay();
        $startDate = $sendingFriday->copy()->subWeek();

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
