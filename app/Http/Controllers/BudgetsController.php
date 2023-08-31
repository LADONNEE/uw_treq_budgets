<?php

namespace App\Http\Controllers;

use App\Forms\Budget\BudgetForm;
use App\Models\Allocation;
use App\Models\Budget;
use App\Models\BudgetBiennium;
use App\Models\BudgetLog;
use App\Services\BienniumsService;

class BudgetsController extends AbstractController
{
    /**
     * @var BienniumsService
     */
    private $bienniums;

    public function __construct(BienniumsService $bienniums)
    {
        $this->bienniums = $bienniums;
    }

    public function index()
    {
        return $this->biennium(null);
    }

    public function biennium($biennium)
    {
        $biennium = $this->bienniums->valid($biennium);
        $query = BudgetBiennium::orderBy('budgetno')
            ->where('biennium', $biennium)
            ->with('business');
        if (wantsCsv()) {
            $budgets = $query->with('purpose')->with('uw')->get();
            return response()->view('budgets/csv', compact('budgets'));
        }
        $budgets = $query->get();
        $bienniums = $this->bienniums->budgetScope();
        return view('budgets/index', compact('biennium', 'bienniums', 'budgets'));
    }

    public function show(Budget $budget)
    {
        $assignLog = BudgetLog::where('budget_id', $budget->id)->get();
        $allocations = Allocation::where('budget_id', $budget->id)->get();
        return view('budgets/show', compact('budget', 'assignLog', 'allocations'));
    }

    public function edit(Budget $budget)
    {
        $this->authorize('budget:fiscal');
        $form = new BudgetForm($budget);
        return view('budgets/edit', compact('budget', 'form'));
    }

    public function update(Budget $budget)
    {
        $this->authorize('budget:fiscal');
        $form = new BudgetForm($budget);
        if ($form->process()) {
            return redirect()->action('BudgetsController@show', $budget->id);
        }
        return redirect()->action('BudgetsController@edit', $budget->id);
    }

}
