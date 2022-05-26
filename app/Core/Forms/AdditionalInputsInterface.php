<?php
/**
 * @package edu.uw.org.pcore
 */

/**
 * Object that can manage a subset of inputs on a form
 */

namespace App\Core\Forms;

interface AdditionalInputsInterface
{
    public function addInputs();

    public function validate();

    public function getValues();
}
