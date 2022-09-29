<?php
namespace Uworgws\Formkit;

use Uworgws\Formkit\ValueHandlers\BaseValueHandler;
use Uworgws\Formkit\ValueHandlers\CarbonDateValue;
use Uworgws\Formkit\ValueHandlers\TextValue;

class Input
{
    private $error;
    private $name;
    private $type;
    private $valueObj;

    /** @var InputOptions */
    private $options;

    /** @var InputView */
    private $view;

    public function __construct($name, $type = 'text', ?BaseValueHandler $valueHandler = null)
    {
        $this->name = $name;
        $this->type = $type;
        $vh = $valueHandler ?? new TextValue();
        $vh->setNotValidCallback([$this, 'error']);
        $this->valueObj = new InputValue($vh);
    }

    /**
     * Set text provided to user next to a single boolean checkbox input
     * This text is used when rendering a 'boolean' input type as HTML, it will describe
     * the truthy condition indicated when the box is checked or switch is on, etc
     * @param string $text
     * @return Input
     */
    public function booleanText($text)
    {
        $this->set('booleanText', $text);
        return $this;
    }

    public function getFormValue()
    {
        return $this->valueObj->getFormValue();
    }

    public function setFormValue($value)
    {
        $this->valueObj->setUserInput($value);
    }

    public function setUserInput($value)
    {
        $this->error = null;
        $this->valueObj->setUserInput($value);
    }

    public function getModelValue()
    {
        $maxlength = $this->getInputView()->get('maxlength');

        return ($maxlength)
            ? substr($this->valueObj->getModelValue(), 0, $maxlength)
            : $this->valueObj->getModelValue();
    }

    public function setModelValue($value)
    {
        $this->valueObj->setModelValue($value);
    }

    public function isEmpty()
    {
        return $this->valueObj->isEmpty();
    }

    public function options($options)
    {
        $this->useOptions();
        $this->options->setOptions($options);
        return $this;
    }

    /**
     * Provide a value to be added in front of the $options list
     * Fluent interface
     * @param string $display
     * @param string $value
     * @return $this
     */
    public function firstOption($display, $value = '')
    {
        $this->useOptions();
        $this->options->addFirstOption($display, $value);
        return $this;
    }

    /**
     * Provide a value to be added after the $options list
     * Fluent interface
     * @param string $display
     * @param string $value
     * @return $this
     */
    public function lastOption($display, $value = '')
    {
        $this->useOptions();
        $this->options->addLastOption($display, $value);
        return $this;
    }

    public function getOptions()
    {
        if ($this->options instanceof InputOptions) {
            return $this->options->all();
        }
        return [];
    }

    public function getType()
    {
        return $this->type;
    }

    public function class($value)
    {
        $this->set('class', $value);
        return $this;
    }

    public function date($dateFormat = 'n/j/Y', $notValidMessage = 'Not a valid date, use M/D/YYYY')
    {
        $this->valueHandler(new CarbonDateValue($dateFormat, $notValidMessage));
        return $this;
    }

    public function error($message)
    {
        $this->error = $message;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function hasError()
    {
        return ! empty($this->error);
    }

    public function getInputView(): InputView
    {
        $this->useView();
        return $this->view;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function help($value)
    {
        $this->set('help', $value);
        return $this;
    }

    public function idSuffix($suffix)
    {
        $this->set('id', "{$this->name}_{$suffix}");
        return $this;
    }

    public function initialValue($value)
    {
        $this->setModelValue($value);
        return $this;
    }

    public function label($value)
    {
        $this->set('label', $value);
        return $this;
    }

    public function required($value = true)
    {
        $this->set('required', $value);
        return $this;
    }

    public function toArray(): array
    {
        $this->useView();
        $vars = $this->view->vars();
        return [
            'name' => $this->name,
            'type' => $this->type,
            'valueType' => $this->valueObj->valueTypeName(),
            'label' => $vars['label'],
            'maxlength' => $vars['maxlength'] ?? null,
            'required' => $vars['required'],
            'value' => $this->getFormValue(),
            'hasError' => $this->hasError(),
            'error' => $this->error,
            'options' => $this->optionsToArray(),
        ];
    }

    private function optionsToArray(): array
    {
        if ($this->options === null) {
            return [];
        }

        $out = [];

        foreach ($this->options->all() as $value => $label) {
            $out[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $out;
    }

    public function maxlength($value)
    {
        $this->set('maxlength', $value);
        return $this;
    }

    public function type($value)
    {
        $this->set('type', $value);
        return $this;
    }

    public function set($name, $value)
    {
        $this->useView();
        $this->view->set($name, $value);
        return $this;
    }

    /**
     * Return the value of an input property or null
     * Global getter method that exposes both local prop and props stored in InputView object
     * @param string $name
     * @return mixed
     */
    public function getProperty($name)
    {
        switch ($name) {
            case 'error':
                return $this->error;
            case 'name':
                return $this->name;
            case 'type':
                return $this->type;
            case 'value':
                return $this->valueObj->getModelValue();
            case 'options':
                $this->useOptions();
                return $this->options->all();
            default:
                $this->useView();
                return $this->view->get($name);
        }
    }

    public function valueHandler(BaseValueHandler $valueHandler)
    {
        $val = $this->valueObj->getModelValue();
        $valueHandler->setNotValidCallback([$this, 'error']);
        $this->valueObj = new InputValue($valueHandler);
        $this->valueObj->setModelValue($val);
        return $this;
    }

    private function useOptions()
    {
        if ($this->options === null) {
            $this->options = new InputOptions();
        }
    }

    private function useView()
    {
        if (!$this->view instanceof InputView) {
            $this->view = new InputView($this);
        }
    }
}
