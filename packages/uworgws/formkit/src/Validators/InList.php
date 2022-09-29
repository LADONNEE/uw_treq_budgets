<?php
namespace Uworgws\Formkit\Validators;

class InList extends BaseValidator
{
    public function __construct(string $message = 'Choose an option from this list')
    {
        parent::__construct($message);
    }

    public function isValid(string $value, array $options): bool
    {
        return array_key_exists($value, $options);
    }
}
