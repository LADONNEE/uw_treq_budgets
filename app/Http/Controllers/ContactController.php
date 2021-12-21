<?php

namespace App\Http\Controllers;

use App\Forms\ContactPersonForm;
use App\Models\Contact;
use App\Reports\PersonSearch;

class ContactController extends AbstractController
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->suggestAjax();
        }
        $filters = request()->all();
        $report = new PersonSearch($filters);
        return $report->getReport();
    }

    protected function suggestAjax()
    {
        $filters = json_decode(request('q'), true);
        if (count($filters) == 0) {
            return response('[]', 200)->header('Content-Type', 'application/json');
        }
        $report = new PersonSearch($filters);
        $out = $report->getReport();
        return response(json_encode($out), 200)->header('Content-Type', 'application/json');
    }

    public function edit(Contact $contact)
    {
        $this->authorize('budget:fiscal');

        $form = new ContactPersonForm($contact);
        return view('budget.contacts.index', compact('contact', 'form'));
    }

    public function update(Contact $contact)
    {
        $this->authorize('budget:fiscal');
        $facultyMember = $contact;

        $form = new ContactPersonForm($contact);
        if ($form->process()) {
            $referrer = basename($_SERVER['HTTP_REFERER']);

            return response()->json([
                'status' => 202,
                'result' => 'success',
                'defaultBudgetId' => "default-budget-{$contact->id}",
                'defaultBudget' => view('budget.effort._update-faculty-table-default-budget', compact('facultyMember', 'referrer'))->render(),
                'financeManagerId' => "finance-manager-{$contact->id}",
                'financeManager' => view('budget.effort._update-faculty-table-finance-manager', compact('facultyMember', 'referrer'))->render(),
                'is8020Id' => "80_20-{$contact->id}",
                'is8020' => view('budget.effort._update-faculty-table-is8020', compact('facultyMember', 'referrer'))->render(),
            ]);
        }
        return response()->json([
            'status' => 400,
            'result' => 'invalid',
            'errors' => $form->getErrors(),
            'html' => view('budget.contacts.index', compact('contact', 'form'))->render(),
        ]);
    }
}
