<?php

namespace App\Factories;

use App\Models\Contact;
use App\Models\EffortReport;
use Carbon\Carbon;

class EffortReportFactory
{
    public static function makeEffortReport($faculty, $type, $year, $description = null)
    {
        $reportRange = EffortReport::getReportDateRange($type, $year);

        $effortReport = new EffortReport;
        $effortReport->faculty_contact_id = $faculty->id;
        $effortReport->stage = EffortReport::STAGE_BUDGET;
        $effortReport->type = $type;
        $effortReport->creator_contact_id = Contact::personToContact(user()->person_id);
        $effortReport->description = $description;
        $effortReport->start_at = $reportRange['start_at'];
        $effortReport->end_at = $reportRange['end_at'];

        return $effortReport;
    }
}
