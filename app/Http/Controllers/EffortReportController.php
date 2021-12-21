<?php

namespace App\Http\Controllers;

use App\Factories\ApprovalsFactory;
use App\Factories\EffortReportFactory;
use App\Factories\ReportAllocationsFactory;
use App\Models\AllocationsWithCostingTableData;
use App\Models\Approval;
use App\Models\Contact;
use App\Models\EffortReport;
use App\Models\EffortReportAllocation;
use App\Utilities\DefaultFiscalPerson;
use App\Utilities\ReportPeriod;
use Illuminate\Support\Facades\DB;

class EffortReportController extends Controller
{
    public function create(Contact $faculty)
    {
        $reportPeriod = new ReportPeriod(request('report_period'));
        $effortReport = EffortReportFactory::makeEffortReport($faculty, $reportPeriod->type, $reportPeriod->year);
        $allocationFactory = ReportAllocationsFactory::load($effortReport);
        $allocations = $allocationFactory->getReportAllocationsWithoutDefaults();
        $invalidPeriods = $allocationFactory->getInvalidAllocationPeriods();
        $approvals = null;
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();

        if ($allocations->count() > 0) {
            $approvals =  ApprovalsFactory::generateApprovals($reportPeriod->type, $reportPeriod->year, $faculty->id);
        }

        return view('budget/effort/effort-report/create', compact('faculty', 'effortReport', 'allocations', 'reportPeriod', 'approvals', 'defaultFiscalPerson', 'invalidPeriods'));
    }

    public function store(Contact $faculty)
    {
        DB::transaction(function () use ($faculty) {
            $setEffortReport = EffortReportFactory::makeEffortReport($faculty, request('type'), request('year'), request('description'));
            $effortReport = EffortReport::create($setEffortReport->toArray());
            $allocationFactory = ReportAllocationsFactory::load($effortReport);
            $allocations = $allocationFactory->getReportAllocationsWithoutDefaults();
            $allocationsWithDefaults = $allocationFactory->getReportAllocationsWithDefaults();
            $approvals = ApprovalsFactory::generateApprovals(request('type'), request('year'), $faculty->id, $effortReport->id, Approval::RESPONSE_PENDING);

            foreach ($approvals as $approval) {
                Approval::create($approval->toArray());
            }

            foreach ($allocations as $allocation) {
                EffortReportAllocation::create($allocation->toArray());
            }

            foreach ($allocationsWithDefaults as $allocation) {
                if ($allocation->is_automatic) {
                    EffortReportAllocation::create($allocation->toArray());
                }
            }
        });

        $effortReport = EffortReport::latest()->first();
        $effortReport->checkSupersedes($effortReport);

        return redirect()->route('effort-report-show', [$faculty, $effortReport]);
    }

    public function show(Contact $faculty, EffortReport $effortReport)
    {
        $allocations = $effortReport->effortReportAllocations()->where('is_automatic', 0)->get()->sortByDesc('type')->values();
        $allocationsFactory = ReportAllocationsFactory::load($effortReport);
        $allocationsWithDefaults = $allocationsFactory->getReportAllocationsWithDefaults();
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();
        $defaultBudget = $faculty->default_budget_id ?? '';
        $allocationsWithCostingTableData = ReportAllocationsFactory::allocationsWithCostingTableData($allocationsWithDefaults, $defaultFiscalPerson, $defaultBudget);

        $niceAllocationsWithCostingTableData = array_map(function ($row) {
            return new AllocationsWithCostingTableData($row);
        }, $allocationsWithCostingTableData);

        return view('budget/effort/effort-report/show', compact('faculty', 'effortReport', 'allocations', 'allocationsWithDefaults', 'defaultFiscalPerson', 'niceAllocationsWithCostingTableData'));
    }
}
