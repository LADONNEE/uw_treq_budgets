<?php

namespace App\Reports;

use App\Models\EffortReport;

class RevisitsReport extends MyEffortReportsReport
{
    public function load()
    {
        $effortReports = EffortReport::whereNotNull('revisit_at')
            ->whereNull('revisit_notified_at')
            ->get();

        return $effortReports;
    }
}
