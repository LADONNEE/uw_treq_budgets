<?php

class TestableValueHandler extends \Utilws\Formkit\ValueHandlers\BaseValueHandler
{
    public function toModel(string $value)
    {
        return $value . ':model';
    }

    public function fromModelToForm($value): string
    {
        return $value . ':form';
    }
}
