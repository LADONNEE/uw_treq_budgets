<?php
namespace Utilws\Formkit\ValueHandlers;

class BaseValueHandler
{
    /**
     * Optional callback to be used when this handler detects invalid value
     * @var callable|null
     */
    private $notValidCallback;

    /**
     * Brief string describing data type of this value
     * This string is passed to the client-side app in API responses allowing the JS
     * app to attach special input helpers and validation.
     * @return string
     */
    public function valueTypeName(): string
    {
        return 'text';
    }

    /**
     * True if $value received from user input should be interpreted as empty or missing
     * @param string|null $value
     * @return bool
     */
    public function isEmpty($value): bool
    {
        return $value === null || $value === '';
    }

    /**
     * Sanitize value received from user input
     * Destructive, discards extraneous or dangerous parts of input
     * @param string|null $value
     * @return string|null
     */
    public function scrub(string $value): ?string
    {
        return $value;
    }

    /**
     * Convert a scrubbed user input value to representation used by application data model
     * @param string $value
     * @return mixed
     */
    public function toModel(string $value)
    {
        return $value;
    }

    /**
     * Convert a value from application data model to a string presentable in HTML form
     * @param mixed $value
     * @return string
     */
    public function fromModelToForm($value): string
    {
        return (string) $value;
    }

    /**
     * Provide callback to be used when this handler detects invalid value
     * @param callable $callback
     */
    public function setNotValidCallback(callable $callback)
    {
        $this->notValidCallback = $callback;
    }

    /**
     * Use provided callback to notify of a conversion failure due to bad input
     * @param string $message
     */
    protected function notValid($message)
    {
        if (is_callable($this->notValidCallback)) {
            call_user_func($this->notValidCallback, $message);
        }
    }
}
