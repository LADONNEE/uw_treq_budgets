<?php

/**
 * Validate that an input value is a valid date
 * Stores Carbon instance in Input::$parsed
 */

namespace App\Forms\Validation;

use App\Core\Forms\Input;
use Carbon\Carbon;

trait ValidateDateTrait
{

    public function validateDate($input, $error = 'Not a valid date, use M/D/YYYY')
    {
        $input = $this->input($input);
        if (!$input instanceof Input) {
            return false;
        }
        if ($input->isEmpty()) {
            return true;
        }
        $ts = strtotime($input->getValue());
        if ($ts === false) {
            $input->error($error);
            return false;
        }
        $input->parsed(Carbon::createFromTimestamp($ts));
        return true;
    }

}
