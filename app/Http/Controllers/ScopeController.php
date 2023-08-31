<?php

namespace App\Http\Controllers;

class ScopeController
{
    public function index()
    {
        $scope = config('budgets.scope');
        return view('scope/index', compact('scope'));
    }
}
