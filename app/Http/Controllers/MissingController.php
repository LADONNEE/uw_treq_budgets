<?php

namespace App\Http\Controllers;

use App\Forms\Budget\BudgetForm;
use App\Models\Budget;
use App\Models\BudgetBiennium;
use App\Services\BienniumsService;

class MissingController extends AbstractController
{
    /**
     * @var BienniumsService
     */
    private $bienniums;
    private $fields = [
        // 'fiscal_person_id' => true, // Always check this field empty
        'reconciler_person_id' => true,
        'business_person_id' => true,
        'purpose_brief' => true,
        'food' => true,
    ];

    public function __construct(BienniumsService $bienniums)
    {
        $this->bienniums = $bienniums;
    }

    public function index()
    {
        $this->authorize('budget:fiscal');
        $query = $this->buildQuery();

        if (wantsCsv()) {
            $budgets = $query->with('purpose')->get();
            return response()->view('budgets/csv', compact('budgets'));
        }

        $budgets = $query->get();
        return view('missing/index', compact('budgets'));
    }

    public function edit(Budget $budget)
    {
        $this->authorize('budget:fiscal');
        $form = new BudgetForm($budget);
        return view('missing._edit', compact('budget', 'form'));
    }

    public function update(Budget $budget)
    {
        $this->authorize('budget:fiscal');
        $form = new BudgetForm($budget);
        if ($form->process()) {
            return response()->json([
                'status' => 202,
                'result' => 'success',
                'id' => "budget-{$budget->id}",
                'html' => view('missing._row', compact('budget'))->render(),
            ]);
        }
        return response()->json([
            'status' => 400,
            'result' => 'invalid',
            'errors' => $form->getErrors(),
            'html' => view('missing._edit', compact('budget', 'form'))->render(),
        ]);
    }

    private function buildQuery()
    {
        $checkingFields = [];
        foreach ($this->fields as $field => $checking) {
            if ($checking) {
                $checkingFields[] = $field;
            }
        }
        return BudgetBiennium::orderBy('budgetno')
            ->where('biennium', $this->bienniums->current())
            ->where(function ($query) use ($checkingFields) {
                $query->whereNull('fiscal_person_id');
                foreach ($checkingFields as $cf) {
                    $query->orWhereNull($cf);
                }
            })
            ->with('business');
    }
}
