<?php
namespace App\Forms\Validation;

use Uwcoews\Formkit\Input;

trait ValidatesUwNetIdsTrait
{

    public function validateUwnetid($input, $error = 'Not a valid UW NetID')
    {
        $input = $this->input($input);
        if (!$input instanceof Input) {
            return false;
        }
        if ($input->isEmpty()) {
            return true;
        }
        $u = strtolower(trim($input->getFormValue()));
        if (preg_match('/^[a-z][a-z0-9]{0,7}$/', $u)) {
            return true;
        }
        $input->error($error);
        return false;
    }

}
