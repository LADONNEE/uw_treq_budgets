<?php

namespace App\Http\Controllers;

class AboutController extends AbstractController
{

    public function index()
    {
        return view('about/index');
    }

}
