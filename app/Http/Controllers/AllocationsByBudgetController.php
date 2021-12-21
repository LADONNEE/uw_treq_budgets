<?php

namespace App\Http\Controllers;

use App\Forms\AllocationForm;
use App\Models\Allocation;
use App\Models\Budget;
use App\Models\Contact;
use App\Utilities\DefaultFiscalPerson;
use Carbon\Carbon;

class AllocationsByBudgetController
{
    public function index(Budget $budget)
    {
        $allocations = Allocation::where('budget_id', $budget->id)->get();

        return view('budget/effort/allocations-by-budget/index', compact('allocations', 'budget'));
    }

    public function edit(Allocation $allocation, Budget $budget)
    {
        $form = new AllocationForm($allocation);
        $faculty = Contact::where('id', $allocation->faculty_contact_id)->first();
        $allocations = Allocation::where('faculty_contact_id', $faculty->id)
            ->where('end_at', '>', Carbon::now()->subYear())
            ->orderBy('type', 'DESC')
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->get();
        $returnToBudget = true;
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();


        return view('budget/effort/allocations/edit', compact('allocation', 'allocations', 'form', 'budget', 'faculty', 'returnToBudget', 'defaultFiscalPerson'));
    }

    public function update(Allocation $allocation, Budget $budget)
    {
        $form = new AllocationForm($allocation);

        if ($form->process()) {
            return redirect()->route('budget-details', $allocation->budget_id);
        }

        return redirect()->back();
    }

    public function create(Budget $budget)
    {
        $form = new AllocationForm(new Allocation([
            'type' => 'ALLOCATION',
            'budget_id' => $budget>id
        ]));
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson()->getFullName();

        return view('budget/effort/allocations/create', compact('form', 'budget', 'defaultFiscalPerson'));
    }

    public function store()
    {
        $allocation = new Allocation();
        $form = new AllocationForm($allocation);

        if ($form->process()) {
            return redirect()->route('allocations-by-budget', $allocation->budget_id);
        }

        return redirect()->back();
    }
}
