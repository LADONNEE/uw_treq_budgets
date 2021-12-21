<?php

namespace App\Forms\Budget;

use App\Forms\Form;
use App\Forms\Validation\PersonExists;
use App\Models\BudgetPerson;

class BudgetPersonForm extends Form
{
    protected $person;

    public function __construct(BudgetPerson $person)
    {
        $this->person = $person;
    }

    public function createInputs()
    {
        $this->add('action');
        $this->add('person_id');
        $this->add('description');
    }

    public function initValues()
    {
        $this->fill($this->person);
    }

    public function validate()
    {
        (new PersonExists)->isValid('person_id', []);
    }

    public function commit()
    {
        if ($this->value('action') == 'delete') {
            $this->person->delete();
            return;
        }

        $this->person->person_id = $this->value('person_id');
        $this->person->description = $this->value('description');
        $this->person->save();
    }

}
