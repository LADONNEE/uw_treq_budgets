<?php

namespace App\Http\Controllers;

use App\Repositories\Budget\BudgetSearch;
use Illuminate\Http\Request;

class SearchController extends AbstractController
{
    public function index(Request $request)
    {
        $report = new BudgetSearch($request->get('q'));
        $format = $request->get('format');
        if ($format == 'csv') {
            return view('budget.budgets.csv', ['budgets' => $report->budgets()]);
        }
        return view('budget/search/index', compact('report'));
    }
}
