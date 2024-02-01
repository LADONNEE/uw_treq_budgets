<?php
namespace Utilws\Formkit\ValueHandlers;

class TextValue extends BaseValueHandler
{
    public function scrub(?string $value): ?string
    {
        return trim(strip_tags($value));
    }
}
