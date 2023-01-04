<?php

namespace App\Http\Controllers;

use App\Forms\Budget\BudgetForm;
use App\Forms\Budget\ProjectCodeForm;
use App\Models\Budget;
use App\Models\ProjectCode;
use App\Services\BienniumsService;

class ProjectCodeController extends AbstractController
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
        $projectcodes = $query->get();
        
        if (wantsCsv()) {
            return response()->view('budget/projectcode/csv', compact('projectcodes'));
        }

        return view('budget/projectcode/index', compact('projectcodes'));
    }

    public function edit(ProjectCode $projectcode)
    {
        $this->authorize('budget:fiscal');
        $form = new ProjectCodeForm($projectcode);
        return view('budget.projectcode._edit', compact('projectcode', 'form'));
    }

    public function update(ProjectCode $projectcode)
    {
        $this->authorize('budget:fiscal');
        $form = new ProjectCodeForm($projectcode);
        if ($form->process()) {
            return response()->json([
                'status' => 202,
                'result' => 'success',
                'id' => "projectcode-{$projectcode->id}",
                'html' => view('budget.projectcode._row', compact('projectcode'))->render(),
            ]);
        }
        return response()->json([
            'status' => 400,
            'result' => 'invalid',
            'errors' => $form->getErrors(),
            'html' => view('budget.projectcode._edit', compact('projectcode', 'form'))->render(),
        ]);
    }

    private function buildQuery()
    {
        return ProjectCode::orderBy('id');
    }
}
