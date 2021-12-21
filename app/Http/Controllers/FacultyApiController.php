<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class FacultyApiController extends Controller
{
    public function index()
    {
        $query = request('q');

        return response()->json(
            Contact::select('id', 'firstname', 'lastname', 'uwnetid')
                ->where('is_faculty', true)
                ->where('firstname', 'LIKE', $query . '%')
                ->orWhere('lastname', 'LIKE', $query . '%')
                ->get()
        );
    }
}
