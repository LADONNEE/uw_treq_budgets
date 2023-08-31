<?php

namespace App\Http\Controllers;

use App\Reports\WorktagTree;

class HierarchyController extends Controller
{
    public function index($type = 'cost-centers')
    {
        $this->authorize('uwft');

        if ($type === 'programs') {
            $tree = new WorktagTree('Programs', 'Program');
        } else {
            $tree = new WorktagTree('Cost Centers', 'CostCenter');
        }

        return view('hierarchy.index', compact('tree'));
    }
}
