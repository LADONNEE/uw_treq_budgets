<?php
namespace Utilws\Formkit\ValueHandlers;

class UnixTimeStampValue extends TextValue
{
    protected $dateFormat;
    protected $notValidMessage;

    public function __construct($dateFormat = 'n/j/Y', $notValidMessage = 'Not a valid date, use M/D/YYYY')
    {
        $this->dateFormat = $dateFormat;
        $this->notValidMessage = $notValidMessage;
    }

    public function toModel(string $value)
    {
        $ts = strtotime($value);
        if ($ts === false) {
            $this->notValid($this->notValidMessage);
            return null;
        }
        return $ts;
    }

    public function fromModelToForm($value): string
    {
        return date($this->dateFormat, $value);
    }

    public function valueTypeName(): string
    {
        return 'date';
    }
}
