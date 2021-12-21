<?php

namespace App\Http\Controllers;

use App\Models\Person;

class PeopleApiController extends Controller
{
    public function index()
    {
        $query = request('q');

        return response()->json(
            Person::select('person_id', 'firstname', 'lastname', 'uwnetid')
                ->where('firstname', 'LIKE', $query . '%')
                ->orWhere('lastname', 'LIKE', $query . '%')
                ->get()
        );
    }
}
