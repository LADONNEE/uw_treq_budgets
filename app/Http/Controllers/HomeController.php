<?php

namespace App\Http\Controllers;

use App\Models\EdwBudget;

class HomeController extends AbstractController
{

    public function index()
    {
        $person = viewAs();
        $budgets = $this->getMyBudgets($person->person_id);
        return view('budget/home/index', compact('person', 'budgets'));
    }


    public function getMyBudgets($person_id)
    {
        $biennium = app('bienniums')->valid(null);
        return EdwBudget::select('edw_budget_cache.*')
            ->where('BienniumYear', $biennium)
            ->orderBy('BudgetNbr')
            ->with('fiscal')
            ->get();
    }

    /*
    public function getMyBudgets($person_id)
    {
        $biennium = app('bienniums')->valid(null);
        return EdwBudget::select('edw_budget_cache.*')
            ->leftJoin('watchers', function($join) use($person_id){
               $join->on('edw_budget_cache.id', '=', 'watchers.budget_id')
                   ->where('watchers.person_id', $person_id);
            })
            ->where('BienniumYear', $biennium)
            ->where(function($query) use($person_id) {
                $query->where('watchers.person_id', $person_id)
                    ->orWhere('edw_budget_cache.fiscal_person_id', $person_id)
                    ->orWhere('edw_budget_cache.pi_person_id', $person_id);
            })
            ->orderBy('BudgetNbr')
            ->with('fiscal')
            ->get();
    }
    */
}
