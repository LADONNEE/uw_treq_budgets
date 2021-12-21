<?php

namespace App\Http\Controllers;

use App\Forms\Budget\NoteForm;
use App\Models\Budget;
use App\Models\Note;

class NotesController extends AbstractController
{

    public function index(Budget $budget)
    {
        $viewData = [
            'addNoteUrl' => action('NotesController@create', $budget->id),
            'refreshUrl' => action('NotesController@index', $budget->id),
            'notes' => $budget->notes,
            'editNoteAction' => function ($id) {
                return action('NotesController@edit', $id);
            }
        ];
        return view('components._notes-items', $viewData);
    }

    public function destroy(Note $note)
    {
        $this->authorize('budget:fiscal');
        $note->delete();
        return redirect()->action('BudgetsController@show', [$note->budget_id]);
    }

    public function create(Budget $budget)
    {
        $note = new Note();
        $note->budget_id = $budget->id;
        $form = new NoteForm($note);
        if (request()->ajax()) {
            return view('budget/notes/_form', compact('budget', 'form'));
        }
        return view('budget/notes/create', compact('budget', 'form'));
    }

    public function store(Budget $budget)
    {
        $note = new Note();
        $note->budget_id = $budget->id;
        $form = new NoteForm($note);
        if ($form->process()) {
            if (request()->ajax()) {
                return response()->json(['status' => 'OK']);
            }
            return redirect()->action('BudgetsController@show', $budget->id);
        }
        return redirect()->action('NotesController@create', $budget->id);
    }

    public function edit(Note $note)
    {
        $form = new NoteForm($note);
        $budget = $note->budget;
        return view('budget/notes/edit', compact('budget', 'note', 'form'));
    }

    public function update(Note $note)
    {
        if (request('action') == 'destroy') {
            return $this->destroy($note);
        }
        $form = new NoteForm($note);
        if ($form->process()) {
            return redirect()->action('BudgetsController@show', $note->budget_id);
        }
        return redirect()->action('NotesController@create', $note->budget_id);
    }

}
