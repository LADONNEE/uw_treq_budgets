<?php
namespace App\Events;

use App\Models\EffortReport;

class EffortReportUpdated
{
    public $effortReport;

    public function __construct(EffortReport $effortReport)
    {
        $this->effortReport = $effortReport;
    }
}
