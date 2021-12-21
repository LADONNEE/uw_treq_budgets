<?php
namespace App\Forms\Validation;

use App\Models\Person;
use Uwcoews\Formkit\Validators\BaseValidator;

class PersonExists extends BaseValidator
{
    public function isValid(string $value, array $options): bool
    {
        $person = Person::find($value);
        return $person instanceof Person;
    }
}
