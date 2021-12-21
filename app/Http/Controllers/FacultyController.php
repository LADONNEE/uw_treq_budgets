<?php

namespace App\Http\Controllers;

use App\Forms\FacultyForm;
use App\Models\Contact;

class FacultyController extends Controller
{
    public function index()
    {
        $this->authorize('manage-faculty');
        $faculty = Contact::where('is_faculty', 1)
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();
        $activeFaculty = Contact::activeContacts($faculty);
        $inactiveFaculty = Contact::inactiveContacts($faculty);

        return view('budget/faculty/index', compact('activeFaculty', 'inactiveFaculty'));
    }

    public function create()
    {
        $form = new FacultyForm(new Contact());

        return view('budget/faculty/create', compact('form'));
    }

    public function store()
    {
        $form = new FacultyForm(new Contact());

        if ($form->process()) {
            return redirect()->route('faculty-index');
        }

        return redirect()->back();
    }

    public function edit(Contact $faculty)
    {
        $form = new FacultyForm($faculty);

        return view('budget/faculty/edit', compact('form', 'faculty'));
    }

    public function update(Contact $faculty)
    {
        $form = new FacultyForm($faculty);

        if ($form->process()) {
            return redirect()->route('faculty-index');
        }

        return redirect()->back();
    }
}
