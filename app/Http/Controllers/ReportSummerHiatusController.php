<?php

namespace App\Http\Controllers;

use App\Models\EffortReport;
use App\Reports\HomeCollection;
use Carbon\Carbon;

class ReportSummerHiatusController extends Controller
{
    public function index()
    {
        $this->authorize('budget:fiscal');
        $now = Carbon::now();
        $year = request('year') ? request('year') : $now->year;
        $period = 'SUMMER';
        $reportDateRange = EffortReport::getReportDateRange($period, $year);
        $reports = new HomeCollection(user(), $period, $year);
        $report = $reports->summerHiatus;
        $dates = EffortReport::all()->where('type', $period)->map(function ($report) use ($period) {
            return ['period' => $report->type, 'start_at' => $report->start_at, 'year' => Carbon::parse($report->start_at)->year];
        })->unique('start_at');

        if (wantsCsv()) {
            return response()->view('budget/effort/summer-hiatus-csv', compact('report', 'period', 'year'));
        }

        return view('budget/effort/reports/summer-hiatus/index', compact('report', 'dates', 'period', 'year', 'reportDateRange'));
    }
}
