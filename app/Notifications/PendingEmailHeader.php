<?php

namespace App\Notifications;

use App\Models\EffortReport;

class PendingEmailHeader
{
    public $title;
    public $report_id;
    public $faculty_id;
    public $to;
    public $type;

    public function __construct(EffortReport $effortReport, $to, $type)
    {
        $this->title = $effortReport->title();
        $this->report_id = $effortReport->id;
        $this->faculty_id = $effortReport->faculty_contact_id;
        $this->to = $to;
        $this->type = $type;
    }
}
