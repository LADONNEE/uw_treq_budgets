<?php

namespace App\Forms\Budget;

use App\Forms\Form;
use App\Models\Note;

class NoteForm extends Form
{
    protected $_note;

    public function __construct(Note $note)
    {
        $this->_note = $note;
    }

    public function createInputs()
    {
        $this->add('note', 'textarea');
    }

    public function initValues()
    {
        $this->input('note')->setFormValue($this->_note->note);
    }

    public function validate()
    {
        $this->check('note')->notEmpty();
    }

    public function commit()
    {
        $this->_note->note = $this->value('note');
        $this->_note->editedBy(user()->person_id);
        $this->_note->save();
    }

}
