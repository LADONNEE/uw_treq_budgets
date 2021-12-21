<?php

namespace App\Reports;

use App\Models\Contact;
use App\Models\EffortReport;
use App\Models\EffortReportAllocation;
use Carbon\Carbon;

class SummerHiatusReport extends MyEffortReportsReport
{
    public function load()
    {
        $dateRange = EffortReport::getReportDateRange($this->period, $this->year);
        $allSummerEffortReports = EffortReport::getApprovedSummerEffortReports($dateRange);
        $summerHiatusAllocations = collect();
        $totalHiatusReportData = collect();

        foreach ($allSummerEffortReports as $effortReport) {
            $summerHiatusAllocations = $summerHiatusAllocations->concat($effortReport->effortReportAllocations
                ->where('allocation_category', EffortReportAllocation::TYPE_HIATUS)
                ->where('allocation_percent', 100)
            );
        }

        $summerHiatusAllocations->map(function ($allocation) {
            $allocation['firstname'] = $allocation->effortReport->faculty->firstname;
            $allocation['lastname'] = $allocation->effortReport->faculty->lastname;
            return $allocation;
        });

        // get all active faculty from contacts, minus faculty with effort reports for this period
        $facultyNoEffort = Contact::select('contacts.*')
            ->where('contacts.is_faculty', '1')
            ->where(function ($query) {
                $query->where('contacts.end_at', '>=', Carbon::now())
                    ->orWhereNull('contacts.end_at');
            })
            ->whereNotExists(function ($query) use ($dateRange) {
                $query->select('*')
                    ->from('effort_reports AS r')
                    ->whereColumn('contacts.id', 'r.faculty_contact_id')
                    ->where('r.type', $this->period)
                    ->where('r.start_at', $dateRange['start_at']->toDateString())
                    ->where('r.stage', EffortReport::STAGE_APPROVED);
            })
            ->get();
        $totalHiatusReportData = $totalHiatusReportData->merge($summerHiatusAllocations)->merge($facultyNoEffort)->sortBy('lastname');

        return $totalHiatusReportData->sortBy('lastname');
    }
}
