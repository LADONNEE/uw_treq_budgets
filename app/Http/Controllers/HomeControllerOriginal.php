<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Reports\HomeCollection;
use App\Workflows\OrderTypes;

class HomeControllerOriginal extends Controller
{
    public function index()
    {
//        $types = (new OrderTypes())->typesThreeByTwo();
//        $reports = new HomeCollection(user());
//        $projects = Project::get();
        return view('home.index');
    }
}
