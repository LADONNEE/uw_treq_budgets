<?php

namespace App\Contracts;

interface HasNames
{
    public function getFirst();

    public function getLast();

    public function getIdentifier();
}
