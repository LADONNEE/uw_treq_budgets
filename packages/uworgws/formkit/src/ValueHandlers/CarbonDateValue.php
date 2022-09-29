<?php
namespace Uworgws\Formkit\ValueHandlers;

use Carbon\Carbon;

class CarbonDateValue extends UnixTimeStampValue
{
    public function toModel(string $value)
    {
        $ts = strtotime($value);
        if ($ts === false) {
            $this->notValid($this->notValidMessage);
            return null;
        }
        return Carbon::createFromTimestamp($ts);
    }

    public function fromModelToForm($value): string
    {
        if ($value instanceof Carbon) {
            return $value->format($this->dateFormat);
        }
        return (string) $value;
    }

    public function valueTypeName(): string
    {
        return 'date';
    }
}
