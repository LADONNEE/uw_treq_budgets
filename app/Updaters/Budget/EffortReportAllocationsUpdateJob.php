<?php

namespace App\Updaters\Budget;

use App\Factories\ReportAllocationsFactory;
use App\Models\EffortReportAllocation;
use Illuminate\Support\Facades\DB;

class EffortReportAllocationsUpdateJob
{
    public function run()
    {
        DB::table('effort_reports')->select('*')
            ->chunkById(100, function ($reports) {
                foreach ($reports as $report) {
                    $allocationFactory = ReportAllocationsFactory::load($report);
                    $allocationsWithDefaults = $allocationFactory->getReportAllocationsWithDefaults();
                    $existingDefaultAllocations = EffortReportAllocation::where('report_id', $report->id)->where('is_automatic', 1)->get();

                    foreach ($allocationsWithDefaults as $allocation) {
                        $matchingAllocation = $existingDefaultAllocations->where('start_at', $allocation->start_at->toDateString())
                                                ->where('end_at', $allocation->end_at->toDateString())
                                                ->where('allocation_category', $allocation->allocation_category)
                                                ->where('allocation_percent', $allocation->allocation_percent)
                                                ->first();

                        if ($allocation->is_automatic  && !$matchingAllocation) {
                            EffortReportAllocation::create($allocation->toArray());
                        }
                    }
                }
            });
    }
}
