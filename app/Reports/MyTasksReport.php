<?php

namespace App\Reports;

use App\Models\Approval;
use App\Models\Contact;
use App\Models\EffortReport;

class MyTasksReport extends MyEffortReportsReport
{
    public function load()
    {
        $effortReports = collect([]);

        // Tasks for anyone with the Workday role
        if (user()->hasRole('workday')){
            $coepayContactId = Contact::where('uwnetid', 'coepay')->pluck('id');
            $coepayApprovals = Approval::where('response', Approval::RESPONSE_PENDING)
                ->where('assigned_to_contact_id', $coepayContactId)
                ->orderBy('created_at', 'DESC')
                ->get();

            foreach ($coepayApprovals as $approval) {
                if (!$approval->effortReport->isComplete() && $approval->type === $approval->effortReport->stage) {
                    $effortReports[] = $approval->effortReport;
                }
            }

            $revisits = EffortReport::whereNotNull('revisit_at')
                ->where('revisit_at', '<=', now())
                ->whereNull('revisit_notified_at')
                ->get();

            foreach ($revisits as $revisit) {
                $effortReports[] = $revisit;
            }
        }

        $approvals = Approval::where('response', Approval::RESPONSE_PENDING)
            ->where('assigned_to_contact_id', Contact::personToContact($this->person_id))
            ->orderBy('created_at', 'DESC')
            ->get();

        foreach ($approvals as $approval) {
            if (!$approval->effortReport->isComplete() && $approval->type === $approval->effortReport->stage) {
                $effortReports[] = $approval->effortReport;
            }
        }

        return $effortReports;
    }
}
