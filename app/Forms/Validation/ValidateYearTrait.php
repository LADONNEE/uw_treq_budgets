<?php
/**
 * @package edu.uw.uaa.college
 */

/**
 * Validate that an input value is a valid year value
 */

namespace App\Forms\Validation;

use App\Core\Forms\Input;

trait ValidateYearTrait
{

    public function validateYear($input, $error = 'Not a valid year, use YYYY')
    {
        $input = $this->toInput($input);
        if (!$input instanceof Input) {
            return false;
        }
        if ($input->isEmpty()) {
            return true;
        }
        $value = validYear($input->getValue());
        if ($value) {
            $input->parsed($value);
            return true;
        }
        $input->error($error);
        return false;
    }

}
