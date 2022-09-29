<?php
namespace Uworgws\Formkit\Validators;

use Uworgws\FormKit\Input;

abstract class BaseValidator
{
    private $message;

    public function __construct(string $message = 'Input is not valid')
    {
        $this->message = $message;
    }

    /**
     * True if non-null string $value is a valid value
     * @param string $value
     * @param array $options
     * @return bool
     */
    abstract public function isValid(string $value, array $options): bool;

    final public function validate(Input $input)
    {
        $valid = $this->isValidMixed($input->getModelValue(), $input->getOptions());
        if (!$valid) {
            $input->error($this->message);
        }
        return $valid;
    }

    private function isValidMixed($value, array $options): bool
    {
        if ($value === null) {
            return $this->isValidNull($options);
        }
        if (is_array($value)) {
            return $this->isValidList($value, $options);
        }
        return $this->isValid($value, $options);
    }

    /**
     * Validate an array of values
     * True if all values in list pass validation. False if any value in list fails.
     * @param array $values
     * @param array $options
     * @return bool
     */
    public function isValidList(array $values, array $options): bool
    {
        foreach ($values as $value) {
            if (!$this->isValid($value, $options)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate a null value
     * In general specific validators should ignore null values. This supports composition, an input could
     * have a ValidDate validator, but still allow null while a second input validates ValidDate + NotEmpty.
     * @param array $options
     * @return bool
     */
    public function isValidNull(array $options): bool
    {
        return true;
    }
}
