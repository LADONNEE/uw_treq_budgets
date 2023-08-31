<?php

namespace App\Http\Controllers;

use App\Forms\AllocationForm;
use App\Models\Allocation;
use App\Models\Contact;
use App\Models\EffortReport;
use App\Utilities\DefaultFiscalPerson;
use Carbon\Carbon;

class AllocationsByFacultyController
{
    public function index(Contact $faculty)
    {
        $now = Carbon::now();
        $allocations = Allocation::where('faculty_contact_id', $faculty->id)
            ->where('end_at', '>', Carbon::now()->subMonth())
            ->orderBy('type', 'DESC')
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->get();
        $effortReports = EffortReport::getReportsByFaculty($faculty->id);
        $activeEffortReports = EffortReport::getLatestAndActiveReportsByFaculty($faculty->id);
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();

        return view('effort/allocations-by-faculty/index', compact('allocations', 'faculty', 'now', 'effortReports', 'activeEffortReports', 'defaultFiscalPerson'));
    }
}
