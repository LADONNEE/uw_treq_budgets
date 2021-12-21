<?php

namespace App\Http\Controllers;

use App\Models\EffortReport;
use App\Reports\HomeCollection;
use Carbon\Carbon;

class ReportApprovedAllocationsController extends Controller
{
    public function index()
    {
        $this->authorize('budget:fiscal');
        $now = Carbon::now();
        $year = request('year') ? request('year') : $now->year;
        $period = request('period') ? request('period') : EffortReport::getCurrentPeriod($now);
        $reports = new HomeCollection(user(), $period, $year);
        $report = $reports->allReportAllocations;
        $dates = EffortReport::all()->map(function ($report) {
            return ['period' => $report->type, 'start_at' => $report->start_at, 'year' => Carbon::parse($report->start_at)->year];
        })->unique('start_at');

        if (wantsCsv()) {
            return response()->view('budget/effort/effort-report-allocations-csv', compact('report', 'period', 'year'));
        }

        return view('budget/effort/reports/approved-allocations/index', compact('report', 'dates', 'period', 'year'));
    }
}
