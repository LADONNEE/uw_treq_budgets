<?php
/**
 * @package edu.uw.org.college
 */

/**
 * Form for adding person to give user permissions
 */

namespace App\Forms\Person;

use App\Forms\Form;
use App\Updaters\ContactUpdater;

class AddPersonForm extends Form
{
    public $person_id;
    public $uwnetid;

    public function __construct($uwnetid = null)
    {
        $this->uwnetid = $uwnetid;
    }

    public function createInputs()
    {
        $this->add('id', 'hidden');
        $this->add('person_id', 'hidden');
        $this->add('uwnetid')
            ->required(true)
            ->setFormValue($this->uwnetid);
        $this->add('firstname');
        $this->add('lastname')
            ->required(true);
        $this->unflash();
    }

    public function validate()
    {
        if ($this->input('uwnetid')->isEmpty()) {
            $this->input('uwnetid')->error('UW NetID is required');
        }
        if ($this->input('lastname')->isEmpty()) {
            $this->input('lastname')->error('Last name is required');
        }
    }

    public function commit()
    {
        if ($this->value('person_id')) {
            return;
        }
        $contact = ContactUpdater::updateOrCreateContact([
            'uwnetid' => $this->value('uwnetid'),
            'firstname' => $this->value('firstname'),
            'lastname' => $this->value('lastname'),
        ]);
        $this->person_id = $contact->person_id;
    }
}
