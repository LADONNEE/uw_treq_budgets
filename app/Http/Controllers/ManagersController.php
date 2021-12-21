<?php

namespace App\Http\Controllers;

use App\Forms\Budget\ManagerForm;
use App\Models\Budget;

class ManagersController extends AbstractController
{

    public function edit(Budget $budget)
    {
        $form = new ManagerForm($budget);
        if (request()->ajax()) {
            return view('budget/managers/sidebar', compact('budget', 'form'));
        }
        return view('budget/managers/index', compact('budget', 'form'));
    }

    public function update(Budget $budget)
    {
        $form = new ManagerForm($budget);
        $form->process();

        return response()->json([
            'budget_id' => $budget->id,
            'manager' => eFirstLast($budget->manager)
        ]);
    }

}
