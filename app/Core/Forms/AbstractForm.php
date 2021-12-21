<?php
/**
 * @package edu.uw.education.pcore
 */

/**
 * Collection of input objects with
 */

namespace App\Core\Forms;


abstract class AbstractForm
{
    protected $_spoofableMethods = ['PUT', 'PATCH', 'DELETE'];
    protected $_spoofMethod;
    protected $_attributes = [
        'method' => 'POST',
        'action' => null,
    ];
    /* @var $_inputs Input[] */
    protected $_inputs;

    private $_suffix;

    /**
     * Virtual property accessor for Input instances
     * @param $name
     * @return Input|null
     */
    public function __get($name)
    {
        if ($this->hasInput($name)) {
            return $this->_inputs[$name];
        }
        return null;
    }

    /**
     * Virtual property setter for Input instances
     * Sets the Input->name property to the name of this virtual property
     * Example:
     *   $this->foo = new Input('bar');
     *   echo $this->foo->getName();
     *   // outputs "foo"
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if ($value instanceof Input) {
            $value->name($name);
            $this->addInput($value);
            return;
        }
    }

    /**
     * Instantiate new Input, add to collection, and return the Input instance
     * @param string $name
     * @param string $label
     * @param string $type
     * @return Input
     */
    public function add($name, $label = '', $type = 'text')
    {
        $input = new Input($name, $label, $type);
        $this->addInput($input);
        return $input;
    }

    /**
     * Add an input to the collection
     * @param Input $input
     */
    public function addInput(Input $input)
    {
        $input->setIdSuffix($this->suffix());
        $this->_inputs[$input->getName()] = $input;
        if ($input->getType() == 'file') {
            $this->_attributes['enctype'] = 'multipart/form-data';
        }
    }

    /**
     * Return the HTML for this form's closing tag
     * @return string
     */
    public function close()
    {
        return '</form>';
    }

    /**
     * Execute this form's purpose, example persist data
     * Generally commit is called from process() method only after validate() passes
     * @return mixed
     */
    abstract public function commit();

    /**
     * Map an array of values to this collection's Input values
     * No sanitization or conversion, trusts that $data contains appropriate values
     * @param $data
     */
    public function fill($data)
    {
        foreach ($data as $name => $value) {
            if ($this->hasInput($name)) {
                $this->_inputs[$name]->value($value);
            }
        }
    }

    /**
     * Assign user input in $data to form inputs using configured santizing routines
     * @param $data
     */
    public function fillUserInput($data)
    {
        foreach ($data as $name => $value) {
            if ($this->hasInput($name)) {
                $this->_inputs[$name]->setUserInput($value);
            }
        }
    }

    /**
     * Return the HTML name property of this form
     * @param $name
     * @return string
     */
    public function getAttribute($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
        return null;
    }

    /**
     * Returns array of stored error message with input name as index
     * @return array
     */
    public function getErrors()
    {
        $out = [];
        foreach ($this->_inputs as $input) {
            if ($input->hasError()) {
                $out[$input->name] = $input->error;
            }
        }
        return $out;
    }

    /**
     * Return the collection of Input objects
     * @return Input[]
     */
    public function getInputs()
    {
        return $this->_inputs;
    }

    /**
     * Input state with values and errors as a structured array
     * Provides JSONable representation for sending state to client-side application
     * @return array
     */
    public function getState()
    {
        $data = [];
        foreach ($this->_inputs as $input) {
            $data[$input->getName()] = $input->getState();
        }
        return $data;
    }

    /**
     * Return array of the values of all collection Inputs
     * @return array
     */
    public function getValues()
    {
        $out = [];
        foreach ($this->_inputs as $name => $input) {
            $out[$name] = $input->getValue();
        }
        return $out;
    }

    /**
     * Returns true if one or more Inputs in the collection have a stored error message
     * @return boolean
     */
    public function hasErrors()
    {
        foreach ($this->_inputs as $input) {
            if ($input->hasError()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if $name identifies an Input in the collection
     * @param $name
     * @return bool
     */
    public function hasInput($name)
    {
        return isset($this->_inputs[$name]);
    }

    /**
     * Return a specific input from the collection
     * Returns null if $name does not exist
     * @param string $name
     * @return Input
     */
    public function input($name)
    {
        return isset($this->_inputs[$name]) ? $this->_inputs[$name] : null;
    }

    /**
     * Provides start point for fluent interface configuration of form inputs
     * $this->lastname = $this->newInput('Last name')
     *      ->required(true)
     *      ->help('Your last name')
     *      ->value('Smith');
     * @param string $label
     * @param string $type
     * @return Input
     */
    public function newInput($label = '', $type = 'text')
    {
        return new Input('', $label, $type);
    }

    /**
     * Return the HTML for this form's opening tag
     * @param string|array $options
     * @return string
     */
    public function open($options)
    {
        if (is_array($options)) {
            foreach ($options as $name => $value) {
                $this->setAttribute($name, $value);
            }
        } else {
            $this->setAttribute('action', $options);
        }
        $attributes = [];
        foreach ($this->_attributes as $attrib => $value) {
            $attributes[] = $attrib . '="' . htmlspecialchars($value) . '"';
        }
        if ($this->_spoofMethod) {
            $spoof = '<input type="hidden" name="_method" value="' . $this->_spoofMethod . '" />';
        } else {
            $spoof = '';
        }
        return '<form ' . implode(' ', $attributes) . '>' . $spoof;
    }

    /**
     * Process user input in $data argument
     * Fills the form using $data and runs validate(). If validation fails returns false. If validation passes
     * runs the commit() method. If commit() signals failure (returns false) this returns false.
     * Otherwise returns true indicating success.
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        $this->fillUserInput($data);
        //$this->autoValidation();
        $this->validate();
        if ($this->hasErrors()) {
            $this->validationFailed();
            return false;
        }
        $result = $this->commit();
        if ($result === false) {
            $this->validationFailed();
            return false;
        }
        return true;
    }

    /**
     * Set an HTML attibute of this form
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value)
    {
        if ($name == 'method') {
            $this->setMethod($value);
            return;
        }
        if (is_null($value)) {
            if (array_key_exists($name, $this->_attributes)) {
                unset($this->_attributes[$name]);
            }
        } else {
            $this->_attributes[$name] = $value;
        }
    }

    /**
     * Test that current values of all inputs are valid
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Called by process method when validate() returns false
     */
    public function validationFailed()
    {
        return;
    }

    /**
     * Return the current value of an input
     * Null if no input by $name exists
     * @param string $name
     * @return mixed|null
     */
    public function value($name)
    {
        if ($this->hasInput($name)) {
            return $this->_inputs[$name]->getValue();
        }
        return null;
    }

    /**
     * Hook for validations that are selected automatically based on Input properties
     */
    protected function autoValidation()
    {
        //
    }

    /**
     * Set the HTTP method this form uses
     * Used in HTML form open tag generation
     * @param $method
     */
    protected function setMethod($method)
    {
        $method = strtoupper($method);
        if (in_array($method, $this->_spoofableMethods)) {
            $this->_spoofMethod = $method;
            $method = 'POST';
        }
        $this->_attributes['method'] = $method;
    }

    /**
     * Generate a mildly random string used to prevent input html id collisions
     * @return string
     */
    protected function suffix()
    {
        if ($this->_suffix === null) {
            $this->_suffix = (new RandomString())->generate(6);
        }
        return $this->_suffix;
    }
}
