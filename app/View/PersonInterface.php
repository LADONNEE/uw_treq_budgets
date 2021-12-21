<?php

/**
 * Interface for person objects that have name fields
 * @author hanisko
 */

namespace App\View;

interface PersonInterface
{
    public function getFirstName();

    public function getLastName();

    public function getIdentifier();
}
