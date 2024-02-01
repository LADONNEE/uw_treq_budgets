<?php
namespace Utilws\Importkit\Exceptions;

use Throwable;

class UnknownParserException extends \Exception
{

    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Unknown Parser '{$name}'", $code, $previous);
    }

}
