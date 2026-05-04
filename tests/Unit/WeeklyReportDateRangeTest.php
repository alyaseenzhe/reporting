<?php

namespace Tests\Unit;

use App\Services\WeeklyReportDateRange;
use Carbon\Carbon;
use Tests\TestCase;

class WeeklyReportDateRangeTest extends TestCase
{
    public function test_it_builds_the_previous_friday_through_thursday_range(): void
    {
        $service = new WeeklyReportDateRange();
        $range = $service->forSendingFriday(Carbon::parse('2026-05-01 01:00:00', 'Asia/Riyadh'));

        $this->assertSame('2026-04-24', $range['start_date']);
        $this->assertSame('2026-04-30', $range['end_date']);
    }
}
