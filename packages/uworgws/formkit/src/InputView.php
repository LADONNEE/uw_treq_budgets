<?php
namespace Uworgws\Formkit;

class InputView
{
    private $exports = [
        'booleanText',
        'class',
        'error',
        'help',
        'helpId',
        'htmlAttributes',
        'id',
        'label',
        'name',
        'options',
        'required',
        'type',
        'value',
        'view',
    ];
    private $inputGetters = [
        'error' => 'getError',
        'name' => 'getName',
        'options' => 'getOptions',
        'type' => 'getType',
        'value' => 'getFormValue',
    ];
    private $booleanAttributes = [
        'disabled',
        'readonly',
        'required',
    ];
    private $values = [];
    private $attributes = [];
    private $input;
    private $builtVars;

    public function __construct(Input $input, $label = null)
    {
        $this->input = $input;
        $this->set('label', $label);
    }

    public function buildVars($with = null)
    {
        if (!is_array($with)) {
            $with = ($with === null) ? [] : ['label' => $with];
        }
        $this->builtVars = [];
        foreach ($this->exports as $name) {
            $this->builtVars[$name] = $this->find($name, $with);
            unset($with[$name]);
        }
        if (!isset($this->builtVars['label'])) {
            $this->builtVars['label'] = ucfirst($this->input->getName());
        }
        if (!$this->builtVars['view']) {
            $this->builtVars['view'] = $this->defaultView();
        }
        if ($this->builtVars['help']) {
            $this->builtVars['helpId'] = "{$this->builtVars['id']}_help";
            $with['aria-describedby'] = "{$this->builtVars['helpId']}";
        }
        $this->builtVars['htmlAttributes'] = $this->attributesToString($with);
    }

    private function find($name, array $with)
    {
        if (isset($with[$name])) {
            return $with[$name];
        }
        if (isset($this->inputGetters[$name])) {
            $getter = $this->inputGetters[$name];
            return $this->input->{$getter}();
        }
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        return null;
    }

    public function view()
    {
        if (is_array($this->builtVars) && isset($this->builtVars['view'])) {
            return $this->builtVars['view'];
        }
        return $this->defaultView();
    }

    private function defaultView()
    {
        return 'inputs.' . $this->input->getType();
    }

    public function vars()
    {
        if (!$this->builtVars) {
            $this->buildVars();
        }
        return $this->builtVars;
    }

    public function get($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function set($name, $value = null)
    {
        if (in_array($name, $this->exports)) {
            $this->values[$name] = $value;
        } elseif (in_array($name, $this->booleanAttributes)) {
            if ($value) {
                $this->attributes[$name] = $name;
            } else {
                unset($this->attributes[$name]);
            }
        } else {
            $this->attributes[$name] = $value;
        }
    }

    private function attributesToString(array $with)
    {
        $out = [];
        if (isset($this->builtVars['required']) && $this->builtVars['required']) {
            $out[] = "required=\"required\"";
        }
        $attrs = array_merge($this->attributes, $with);
        foreach ($attrs as $attr => $value) {
            $out[] = $attr . '="' . htmlspecialchars($value) .'"';
        }
        return implode(' ', $out);
    }
}
