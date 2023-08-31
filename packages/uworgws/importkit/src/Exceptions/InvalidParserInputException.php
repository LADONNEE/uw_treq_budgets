<?php
namespace Uwcoenvws\Importkit\Exceptions;

use Throwable;

class InvalidParserInputException extends \Exception
{
    public $input;

    public function __construct($input = null, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->input = $input;
    }
}
