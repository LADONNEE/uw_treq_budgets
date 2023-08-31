<?php

namespace App\Http\Controllers;

use App\Forms\Costcenter\CostcenterForm;
use App\Models\Allocation;
use App\Models\Budget;
use App\Models\BudgetLog;
use App\Models\Worktag;

class CostcentersController extends AbstractController
{

    public function index()
    {

        $query = Worktag::orderBy('workday_code');


        $costcenters = $query->get();

        if (wantsCsv()) {
            return response()->view('costcenters/csv', compact('costcenters'));
        }
        
        return view('costcenters/index', compact('costcenters'));
    }


    public function show(Worktag $costcenter)
    {
        $assignLog = BudgetLog::where('budget_id', $costcenter->id)->get();
        $allocations = Allocation::where('budget_id', $costcenter->id)->get();
        return view('costcenters/show', compact('costcenter', 'assignLog', 'allocations'));
    }

    public function edit(Worktag $costcenter)
    {
        $this->authorize('budget:fiscal');
        $form = new CostcenterForm($costcenter);
        return view('costcenters/edit', compact('costcenter', 'form'));
    }

    public function update(Worktag $costcenter)
    {
        $this->authorize('budget:fiscal');
        $form = new CostcenterForm($costcenter);
        if ($form->process()) {
            return redirect()->action('CostcentersController@show', $costcenter->id);
        }
        return redirect()->action('CostcentersController@edit', $costcenter->id);
    }

}
