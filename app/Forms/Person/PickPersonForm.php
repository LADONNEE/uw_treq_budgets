<?php
/**
 * @package app.treq.school
 */

/**
 * Form to select existing person record
 * Uses type-ahead input to search by name or UW NetID
 */

namespace App\Forms\Person;

use App\Forms\Form;

class PickPersonForm extends Form
{
    public function createInputs()
    {
        $this->add('person_id', 'hidden');
        $this->add('person_search');
    }

    public function validate()
    {
        if ($this->input('person_id')->isEmpty()) {
            $this->input('person_id')->error('Select a person to continue');
        }
    }

    public function commit()
    {

    }
}
