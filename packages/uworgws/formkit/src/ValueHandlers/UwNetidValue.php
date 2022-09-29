<?php
namespace Uworgws\Formkit\ValueHandlers;

class UwNetidValue extends TextValue
{
    public function scrub(?string $value): ?string
    {
        $uwnetid = strtolower(parent::scrub($value));
        $atloc = strpos($uwnetid, '@');
        if ($atloc !== false) {
            $uwnetid = substr($uwnetid, 0, $atloc);
        }
        return $uwnetid;
    }

    public function valueTypeName(): string
    {
        return 'uwnetid';
    }
}
