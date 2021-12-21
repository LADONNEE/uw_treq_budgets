<?php

namespace App\Forms;

use App\Models\Contact;

class ContactPersonForm extends Form
{
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function createInputs()
    {
        $this->add('default_budget_id', 'hidden');
        $this->add('budget_search');

        $this->add('fiscal_person_id', 'hidden');
        $this->add('contact_search');

        $this->add('is_80_20', 'boolean');
    }

    public function initValues()
    {
        $this->fill([
            'budget_search' =>  $this->contact->default_budget_id ?
                "{$this->contact->defaultBudget->budgetno}" : '',
            'contact_search' => $this->contact->fiscal_person_id ?
                $this->contact->getFiscalPersonName() : '',
            'is_80_20' => $this->contact->is_80_20,
        ]);

        $this->fill($this->contact->toArray());
    }

    public function validate()
    {
        //
    }

    public function commit()
    {
        $this->contact->fill($this->all());
        $this->contact->save();
    }
}
