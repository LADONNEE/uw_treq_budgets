<?php
namespace Utilws\Importkit\Exceptions;

use Throwable;

class RecordNotValidException extends \Exception
{
    public $record;

    public function __construct(string $message = "", $record = null, int $code = 0, Throwable $previous = null)
    {
        $this->record = $record;
        parent::__construct($message, $code, $previous);
    }

}
