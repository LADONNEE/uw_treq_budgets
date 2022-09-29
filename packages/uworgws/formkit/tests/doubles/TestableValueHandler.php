<?php

class TestableValueHandler extends \Uworgws\Formkit\ValueHandlers\BaseValueHandler
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
