<?php

namespace App\Forms;

use App\Forms\Validation\ValidatesUwNetIdsTrait;
use App\Models\Contact;

class FacultyForm extends Form
{
    use ValidatesUwNetIdsTrait;

    private $faculty;

    public function __construct(Contact $faculty)
    {
        $this->faculty = $faculty;
    }

    public function createInputs()
    {
        $this->add('email', 'hidden');
        $this->add('person_id', 'hidden');
        $this->add('employeeid', 'hidden');
        $this->add('uwnetid', 'text')->required();
        $this->add('firstname', 'text')->required();
        $this->add('lastname', 'text')->required();
        $this->add('default_budget_id', 'hidden');
        $this->add('budget_search');
        $this->add('fiscal_person_id', 'hidden');
        $this->add('contact_search');
        $this->add('is_80_20', 'boolean');
        $this->add('end_at', 'text')->date();
    }

    public function initValues()
    {
        $this->fill([
            'contact_search' => $this->faculty->fiscal_person_id ?
                $this->faculty->getFiscalPersonName() : '',
            'budget_search' =>  $this->faculty->default_budget_id ?
                $this->faculty->defaultBudget->budgetno : '',
            'is_80_20' => $this->faculty->is_80_20,
        ]);

        $this->fill($this->faculty->toArray());
    }

    public function validate()
    {
        $this->check('firstname')->notEmpty();
        $this->check('lastname')->notEmpty();
        $this->check('uwnetid')->notEmpty();
        $this->validateUwnetid('uwnetid');
    }

    public function commit()
    {
        $this->faculty->fill($this->all());
        $this->faculty->is_faculty = 1;

        if (!$this->input('is_80_20')->getFormValue() || $this->input('is_80_20')->getFormValue() == "false") {
            $this->faculty->is_80_20 = 0;
        } else {
            $this->faculty->is_80_20 = 1;
        }

        Contact::updateOrCreate(['uwnetid' => $this->faculty->uwnetid], $this->faculty->toArray());
    }
}
