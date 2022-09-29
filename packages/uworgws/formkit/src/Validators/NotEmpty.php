<?php
namespace Uworgws\Formkit\Validators;

class NotEmpty extends BaseValidator
{
    public function __construct(string $message = 'Can not be empty')
    {
        parent::__construct($message);
    }

    public function isValid(string $value, array $options): bool
    {
        return $value !== '' && $value !== null;
    }

    public function isValidList(array $values, array $options): bool
    {
        return count($values) > 0;
    }

    public function isValidNull(array $options): bool
    {
        return false;
    }
}
