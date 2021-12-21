<?php

namespace App\Http\Controllers;

use App\Forms\CancelForm;
use App\Models\EffortReport;
use App\Utilities\DefaultFiscalPerson;
use Illuminate\Http\RedirectResponse;

class CancelController extends Controller
{
    public function edit(EffortReport $effortReport)
    {
        $faculty = $effortReport->faculty;
        $allocations = $effortReport->effortReportAllocations;
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();
        $form = new CancelForm($effortReport);
        return view('budget/effort/cancel/edit', compact('faculty', 'allocations', 'defaultFiscalPerson', 'effortReport', 'form'));
    }

    public function update(EffortReport $effortReport): RedirectResponse
    {
        $form = new CancelForm($effortReport);
        $form->process();
        return redirect()->route('effort-report-show', [$effortReport->faculty_contact_id, $effortReport->id]);
    }

}
