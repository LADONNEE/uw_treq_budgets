<?php

namespace App\Http\Controllers;

class ScopeController
{
    public function index()
    {
        $scope = config('budgets.scope');
        return view('budget/scope/index', compact('scope'));
    }
}
