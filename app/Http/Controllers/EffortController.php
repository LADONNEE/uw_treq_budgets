<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Reports\HomeCollection;

class EffortController extends Controller
{
    public function index()
    {
        $allActiveFaculty = Contact::activeContacts(Contact::where('is_faculty', 1)->orderby('lastname')->get());
        $facultyWithEffort = Contact::activeFacultyWithEffortReports();
        $reports = new HomeCollection(user());

        return view('effort/index', compact('allActiveFaculty', 'facultyWithEffort', 'reports'));
    }
}
