<?php

namespace App\Http\Controllers;

use App\Forms\AllocationForm;
use App\Models\Allocation;
use App\Models\Contact;
use App\Utilities\DefaultFiscalPerson;
use Carbon\Carbon;
use Config;

class AllocationsApiController
{

    protected $table_uw_persons;

    public function __construct()
    {
        $this->table_uw_persons = Config::get('app.database_shared') . '.uw_persons'; 
    }

    public function index(Contact $faculty)
    {
        $allocations = Allocation::where('faculty_contact_id', $faculty->id)
            ->where('end_at', '>', Carbon::now()->subMonth())
            ->leftJoin('budgets', 'allocations.budget_id', 'budgets.id')
            ->leftJoin($this->table_uw_persons, 'budgets.fiscal_person_id', $this->table_uw_persons . '.person_id')
            ->select('allocations.*', $this->table_uw_persons . '.firstname as budget_fiscal_firstname', $this->table_uw_persons . '.lastname as budget_fiscal_lastname')
            ->orderBy('type', 'DESC')
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->with('budget:id,purpose_brief,non_coe_name,budgetno,fiscal_person_id')
            ->with('budgetBiennium:uw_budget_id,budget_id,id,name')
            ->with('contact:id,fiscal_person_id')
            ->get();

        return response()->json([
            'data' => $allocations->toArray(),
            'default_fiscal_person' => DefaultFiscalPerson::defaultFiscalPerson()->getFullName(),
            'faculty_fiscal_person' => $faculty->getFiscalPersonName()
        ]);
    }

    public function edit(Allocation $allocation, Contact $faculty)
    {
        $form = new AllocationForm($allocation);

        return response()->json($form->toArray());
    }

    public function create(Contact $faculty)
    {
        $form = new AllocationForm(new Allocation([
            'type' => 'ALLOCATION',
            'faculty_contact_id' => $faculty->id
        ]));

        return response()->json($form->toArray());
    }

    public function update(Allocation $allocation, Contact $faculty)
    {
        $form = new AllocationForm($allocation);

        if (!$form->process()) {
            return response()->json($form->toArray());
        }
    }

    public function store()
    {
        $allocation = new Allocation();
        $form = new AllocationForm($allocation);

        if (!$form->process()) {
            return response()->json($form->toArray());
        }
    }
}
