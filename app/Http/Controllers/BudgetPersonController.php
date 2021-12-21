<?php

namespace App\Http\Controllers;

use App\Forms\Budget\BudgetPersonForm;
use App\Models\Budget;
use App\Models\BudgetPerson;

class BudgetPersonController extends AbstractController
{
    public function store(Budget $budget)
    {
        $this->authorize('budget:fiscal');
        $form = new BudgetPersonForm(new BudgetPerson(['budget_id' => $budget->id]));
        $form->process();
        return redirect()->action('BudgetsController@show', $budget->id);
    }

    public function update()
    {
        $this->authorize('budget:fiscal');
        $budgetPerson = BudgetPerson::findOrFail(request('budget_person_id'));
        $form = new BudgetPersonForm($budgetPerson);
        $form->process();
        return redirect()->action('BudgetsController@show', $budgetPerson->budget_id);
    }
}
