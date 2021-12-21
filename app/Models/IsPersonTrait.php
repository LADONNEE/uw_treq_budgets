<?php

/**
 * Trait with person functionality
 * Provides common functionality for application Models that have an Eloquent relationship to
 * a single person record through the person() method
 */

namespace App\Models;

trait IsPersonTrait
{

    public function getFirstName()
    {
        return $this->person->firstname;
    }

    public function getLastName()
    {
        return $this->person->lastname;
    }

    public function getIdentifier()
    {
        return $this->person->getIdentifier();
    }

}
