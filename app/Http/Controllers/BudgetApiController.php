<?php
namespace App\Http\Controllers;

use App\Models\BudgetBiennium;

class BudgetApiController extends Controller
{
    public function prefetch()
    {
        $out = BudgetBiennium::select('budget_id as id', 'budgetno', 'name')
            ->where('biennium', setting('current-biennium'))
            ->where('BudgetStatus', '<>', '3')
            ->where('BudgetStatus', '<>', '4')
            ->orderBy('budgetno')
            ->orderBy('name')
            ->get();
        return response()->json($out);
    }
}
