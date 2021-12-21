<?php

namespace App\Http\Controllers;

use App\Searches\GlobalSearch;

class Search extends Controller
{
    public function __invoke()
    {
        $search = new GlobalSearch(request('q'));
        extract($search->getReport());
        $searchTerm = $search->parsedQuery;

        return view('search/index', compact('count', 'budgets', 'projects', 'trips', 'searchTerm'));
    }
}
