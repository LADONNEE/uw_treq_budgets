<?php
namespace App\Utilities;

use App\Models\EffortReport;

class ReportPeriod
{
    public $type;
    public $year;

    public function __construct($slug)
    {
        $reportPeriod = explode('-', $slug);
        $this->type = $reportPeriod[1] === 'A' ? EffortReport::PERIOD_ACADEMIC_YEAR : EffortReport::PERIOD_SUMMER;
        $this->year = $reportPeriod[0];
    }
}
