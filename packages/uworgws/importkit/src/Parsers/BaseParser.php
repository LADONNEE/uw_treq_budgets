<?php
namespace Uwcoenvws\Importkit\Parsers;

use Uwcoenvws\Importkit\Contracts\ParserContract;
use Uwcoenvws\Importkit\Exceptions\InvalidParserInputException;

abstract class BaseParser implements ParserContract
{
    protected $currentInput;
    protected $invalidCallback;

    public function parse($input)
    {
        $this->currentInput = $input;
        $input = $this->scrub($input);

        if ($this->isEmpty($input)) {
            return $this->emptyValue();
        }

        return $this->parseValue($input);
    }

    /**
     * Provide a callback to be used if invalid input for this field is detected.
     * If this callback is not provided an exception is thrown
     * @param callable $callback
     */
    public function setInvalidCallback(callable $callback)
    {
        $this->invalidCallback = $callback;
    }

    /**
     * Run parse routine on a scrubbed and non-empty value
     * @param string $input
     * @return mixed
     */
    abstract protected function parseValue($input);

    /**
     * Modification of source data to be applied before empty-testing and parsing
     * Default trim outer whitespace
     * @param string $input
     * @return string
     */
    protected function scrub($input)
    {
        return trim($input);
    }

    /**
     * Return true if $input represents an empty value in the source data
     * $input has been modified by scrub() routine before empty testing
     * @param string $input
     * @return bool
     */
    protected function isEmpty($input)
    {
        return $input === '' || $input === null;
    }

    /**
     * Representation of empty value in local system
     * @return null
     */
    protected function emptyValue()
    {
        return null;
    }

    /**
     * Call when a parse strategy detects invalid input
     * @param string|null $message
     * @throws InvalidParserInputException
     * @return mixed
     */
    protected function invalid($message = null)
    {
        $message = $message ?? 'Input not in expected format, parse failed';

        if (is_callable($this->invalidCallback)) {
            call_user_func($this->invalidCallback, $this->currentInput, $message);
        } else {
            throw new InvalidParserInputException($message, $this->currentInput);
        }
        return $this->emptyValue();
    }

}
