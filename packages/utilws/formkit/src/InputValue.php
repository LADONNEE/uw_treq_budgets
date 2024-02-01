<?php
namespace Utilws\Formkit;

use Utilws\Formkit\ValueHandlers\BaseValueHandler;

class InputValue
{
    private $handler;
    private $model;
    private $form;

    public function __construct(BaseValueHandler $handler)
    {
        $this->handler = $handler;
    }

    public function getFormValue()
    {
        return $this->form;
    }

    public function setFormValue($value)
    {
        $this->form = $this->scrub($value);
        $this->model = $this->toModel($this->form);
    }

    public function setUserInput($value)
    {
        $this->form = $this->scrub($value);
        $this->model = $this->toModel($this->form);
    }

    public function getModelValue()
    {
        return $this->model;
    }

    public function isEmpty()
    {
        if (is_array($this->model)) {
            return count($this->model) === 0;
        }

        return $this->model === null || $this->model === '';
    }

    public function setModelValue($value)
    {
        $this->form = $this->fromModelToForm($value);
        $this->model = $value;
    }

    public function valueTypeName(): string
    {
        return $this->handler->valueTypeName();
    }

    private function fromModelToForm($value)
    {
        return $this->emptyOrMap('fromModelToForm', $value);
    }

    private function scrub($value)
    {
        return $this->emptyOrMap('scrub', $value);
    }

    private function toModel($value)
    {
        return $this->emptyOrMap('toModel', $value);
    }

    private function emptyOrMap($method, $value)
    {
        // Handle scalar
        if (!is_array($value)) {
            return $this->emptyOrMapItem($method, $value);
        }

        // Handle array
        $out = [];
        foreach ($value as $val) {
            $out[] = $this->emptyOrMapItem($method, $val);
        }
        return $out;
    }

    private function emptyOrMapItem($method, $value)
    {
        if ($this->handler->isEmpty($value)) {
            return null;
        }
        return $this->handler->{$method}($value);
    }
}
