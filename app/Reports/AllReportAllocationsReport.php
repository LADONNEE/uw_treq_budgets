<?php

namespace App\Reports;

use App\Models\EffortReport;
use App\Models\EffortReportAllocation;

class AllReportAllocationsReport extends MyEffortReportsReport
{
    public function load()
    {
        $dateRange = EffortReport::getReportDateRange($this->period, $this->year);
        return EffortReportAllocation::getApprovedReportAllocationsWithinRange($dateRange);
    }
}
