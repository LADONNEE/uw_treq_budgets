<?php

namespace App\Http\Controllers;

use App\Forms\Budget\UserForm;
use App\Forms\Person\AddPersonForm;
use App\Forms\Person\PickPersonForm;
use App\Models\Person;
use App\Reports\UsersReport;

class UsersController extends AbstractController
{
    public function index()
    {
        $users = (new UsersReport())->getReport();
        $form = new PickPersonForm();

        return view('budget/users/index', compact('users', 'form'));
    }

    public function select()
    {
        $uwnetid = request('search_term');
        $p = $this->existingPerson(request('person_id'), $uwnetid);
        if ($p instanceof Person) {
            return $this->edit($p->uwnetid);
        }
        return $this->create($uwnetid);
    }

    public function create($uwnetid)
    {
        $this->authorize('budget:admin');
        $form = new AddPersonForm($uwnetid);
        return view('budget/users/create', compact('form'));
    }

    public function store()
    {
        $this->authorize('budget:admin');
        $form = new AddPersonForm();
        if ($form->process()) {
            return redirect()->action('UsersController@edit', $form->uwnetid);
        }
        return redirect()->action('UsersController@create');
    }

    public function edit($uwnetid)
    {
        $this->authorize('budget:admin');
        $user = user($uwnetid);
        $form = new UserForm($user);

        return view('budget/users/edit', compact('form', 'user'));
    }

    public function update($uwnetid)
    {
        $this->authorize('budget:admin');
        $user = user($uwnetid);
        $form = new UserForm($user);

        if ($form->process()) {
            return redirect()->action('UsersController@index');
        }
        return redirect()->action('UsersController@edit', $uwnetid);
    }

    private function existingPerson($person_id, $uwnetid)
    {
        $p = null;
        if ($person_id) {
            $p = Person::find($person_id);
            if ($p instanceof Person) {
                return $p;
            }
        }
        if ($uwnetid) {
            $p = Person::where('uwnetid', $uwnetid)->first();
            if ($p instanceof Person) {
                return $p;
            }
        }
        return $p;
    }

}
