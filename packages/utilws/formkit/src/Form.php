<?php
namespace Utilws\Formkit;

use Utilws\Formkit\ValueHandlers\BaseValueHandler;

abstract class Form
{
    protected $attributes = [
        'method' => 'POST',
        'action' => null,
    ];
    protected $idHash;
    protected $sessionKey = 'form';
    protected $spoofableMethods = ['PUT', 'PATCH', 'DELETE'];
    protected $spoofMethod;
    protected $wasSetup = false;
    protected $wasValidated = false;

    /* @var $inputs Input[] */
    protected $inputs = [];

    /**
     * Create and configure Inputs and add to the form
     * @return void
     */
    abstract public function createInputs();

    /**
     * Test that current values of all inputs are valid and store error messages on Inputs as needed
     */
    abstract public function validate();

    /**
     * Execute this form's purpose, example persist data
     * Generally commit is called from process() method only after validate() passess
     * @return void|false
     */
    abstract public function commit();

    /**
     * Instantiate new Input, add to collection, and return the Input instance
     * @param string $name
     * @param string $type
     * @param BaseValueHandler|null $valueHandler
     * @return Input
     */
    public function add($name, $type = 'text', ?BaseValueHandler $valueHandler = null)
    {
        $input = new Input($name, $type, $valueHandler);
        $this->addInput($input);
        return $input;
    }

    /**
     * Add an input to the collection
     * @param Input $input
     */
    public function addInput(Input $input)
    {
        $input->idSuffix($this->idSuffix());
        $this->inputs[$input->getName()] = $input;
        if ($input->getType() == 'file') {
            $this->attributes['enctype'] = 'multipart/form-data';
        }
    }

    /**
     * Optionally provide initial values for inputs
     * Called during structured initialization setup() process
     */
    public function initValues()
    {
        // no default behavior
    }

    /**
     * Map an array of values to this collection's Input values
     * No sanitization or conversion, trusts that $data contains appropriate values
     * @param $data
     */
    public function fill($data)
    {
        foreach ($data as $name => $value) {
            if ($this->hasInput($name)) {
                $this->inputs[$name]->setModelValue($value);
            }
        }
    }

    /**
     * Assign user input in $data to form inputs
     * @param array $data
     */
    public function fillUserInput($data)
    {
        $this->clearErrors();
        $this->clearState($this->sessionKey);
        $this->wasValidated = false;

        foreach ($data as $name => $value) {
            if ($this->hasInput($name)) {
                $this->inputs[$name]->setUserInput($value);
            }
        }
    }

    /**
     * Set an error message for an input
     * @param string $name
     * @param string $error
     */
    public function error($name, $error)
    {
        $this->inputOrFail($name)->error($error);
    }

    /**
     * Reset any error messages on all inputs
     */
    public function clearErrors()
    {
        foreach ($this->inputs as $input) {
            $input->error(null);
        }
    }

    /**
     * Return error message for a single input
     * Returns null if input has no error
     * @param string $name
     * @return string|null
     */
    public function getError($name)
    {
        return $this->inputOrFail($name)->getError();
    }

    /**
     * Returns array of stored error message with input name as index
     * If optional $forget argument is true, any errors stashed in session will be cleared
     * @param mixed $forget
     * @return array
     */
    public function getErrors($forget = false)
    {
        $this->lazySetup();
        $out = [];
        foreach ($this->inputs as $input) {
            if ($input->hasError()) {
                $out[$input->getName()] = $input->getError();
            }
        }

        if ($forget) {
            $this->clearState($this->sessionKey);
        }

        return $out;
    }

    /**
     * True if any input has an error message
     * @return bool
     */
    public function hasErrors()
    {
        $this->lazySetup();
        foreach ($this->inputs as $input) {
            if ($input->hasError()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return a specific input from the collection
     * Returns null if $name does not exist
     * @param string $name
     * @return Input|null
     */
    public function input($name)
    {
        $this->lazySetup();
        return (isset($this->inputs[$name])) ? $this->inputs[$name] : null;
    }

    /**
     * Returns true if $name identifies an Input in the collection
     * @param $name
     * @return bool
     */
    public function hasInput($name)
    {
        $this->lazySetup();
        return isset($this->inputs[$name]);
    }

    /**
     * Return the collection of Input objects
     * @return Input[]
     */
    public function getInputs()
    {
        $this->lazySetup();
        return $this->inputs;
    }

    /**
     * Return the InputView collection for an input
     * Optionally updates the InputView using $with argument
     * @param string $name
     * @param null $with
     * @return InputView
     */
    public function inputView($name, $with = null): InputView
    {
        $iv = $this->input($name)->getInputView();
        $iv->buildVars($with);
        return $iv;
    }

    /**
     * Return the model value of a single input
     * @deprecated use ->get()
     * @param string $name
     * @return mixed
     */
    public function value($name)
    {
        return $this->get($name);
    }

    /**
     * Set the model value of a single input
     * Model value will also be converted and used to update form value
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->inputOrFail($name)->setModelValue($value);
    }

    /**
     * Set the form value of a single input
     * Form value will also be converted and used to update model value
     * @param string $name
     * @param mixed $value
     */
    public function formValue($name)
    {
        return $this->inputOrFail($name)->getFormValue();
    }

    /**
     * Set the form value of a single input
     * Form value will also be converted and used to update model value
     * @param string $name
     * @param mixed $value
     */
    public function setFormValue($name, $value)
    {
        $this->inputOrFail($name)->setFormValue($value);
    }

    /**
     * Return true if model value on single input is empty value
     * @param string $name
     * @return bool
     */
    public function isEmpty($name)
    {
        return $this->inputOrFail($name)->isEmpty();
    }

    /**
     * Return the model value of a single input
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->inputOrFail($name)->getModelValue();
    }

    /**
     * Return an associative array of all input model values
     * with input names as array keys
     * @param string $name
     * @return array
     */
    public function all()
    {
        return $this->getModelValues(false, false);
    }

    /**
     * Return an associative array of input model values where input names match $keys
     * @param string|array $keys
     * @return array
     */
    public function only($keys)
    {
        return $this->getModelValues($this->argToArray($keys), false);
    }

    /**
     * Return an associative array of all input model values except inputs where names match $keys
     * @param string $name
     * @return array
     */
    public function without($keys)
    {
        return $this->getModelValues(false, $this->argToArray($keys));
    }

    /**
     * Return form state as array data structure
     * Array data structure is designed for conversion to JSON and JavaScript object, API support
     * @return array
     */
    public function toArray(): array
    {
        $out = [
            'wasValidated' => $this->wasValidated,
            'hasErrors' => $this->hasErrors(),
            'inputs' => [],
        ];
        foreach ($this->inputs as $input) {
            $out['inputs'][] = $input->toArray();
        }
        return $out;
    }

    /**
     * Return array of errors as array data structure
     * Array data structure is designed for conversion to JSON and JavaScript object, API support
     * If optional $forget argument is true, any errors stashed in session will be cleared
     * @param mixed $forget
     * @return array
     */
    public function errorsToArray($forget = false): array
    {
        $out = [];
        foreach ($this->getErrors() as $name => $error) {
            $out[] = [
                'name' => $name,
                'error' => $error,
            ];
        };

        if ($forget) {
            $this->clearState($this->sessionKey);
        }

        return $out;
    }

    private function argToArray($keys)
    {
        if (is_array($keys)) {
            return $keys;
        }
        if ($keys === null || $keys === '') {
            return [];
        }
        return [ $keys ];
    }

    protected function getModelValues($only, $without): array
    {
        $this->lazySetup();
        $all = !$only && !$without;
        $out = [];
        foreach ($this->inputs as $input) {
            $name = $input->getName();
            if ($all || ($only && in_array($name, $only)) || ($without && !in_array($name, $without))) {
                $out[$name] = $input->getModelValue();
            }
        }
        return $out;
    }

    /**
     * Process user input in $data argument
     * Fills the form using $data and runs validate(). If validation fails returns false. If validation passes
     * runs the commit() method. If commit() signals failure (returns false) this returns false.
     * Otherwise returns true indicating overall success.
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        $this->fillUserInput($data);
        $this->validate();
        $this->wasValidated = true;
        if ($this->hasErrors()) {
            $this->handleValidationFailed();
            return false;
        }
        $result = $this->commit();
        if ($result === false) {
            $this->handleCommitFailed();
            return false;
        }
        return true;
    }

    /**
     * Called by process method when commit() returns false
     */
    public function handleCommitFailed()
    {
        $this->flash();
    }

    /**
     * Called by process method when validate() returns false
     */
    public function handleValidationFailed()
    {
        $this->flash();
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
        $this->defaultId();
        $attributes = [];
        foreach ($this->attributes as $attrib => $value) {
            $attributes[] =  $attrib .'="'. htmlspecialchars($value) .'"';
        }
        if ($this->spoofMethod) {
            $spoof = '<input type="hidden" name="_method" value="'.$this->spoofMethod.'" />';
        } else {
            $spoof = '';
        }
        if (function_exists('csrf_field')) {
            $csrf = csrf_field();
        } else {
            $csrf = '';
        }
        return '<form ' . implode(' ', $attributes) . '>' . $csrf . $spoof;
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
     * @param string $name
     * @param \Closure|null $closure
     * @return FluentValidation
     */
    protected function check($name, $closure = null)
    {
        $this->lazySetup();

        if ($closure instanceof \Closure) {
            $i = $this->inputs[$name];
            $closure($i->getModelValue(), [$i, 'error'], $this->all());
        }

        return new FluentValidation($this->inputs[$name]);
    }

    protected function lazySetup()
    {
        if (!$this->wasSetup) {
            $this->wasSetup = true;
            $this->createInputs();
            $this->initValues();
            $this->unflash();
        }
    }

    /**
     * Store input values and error messages to session
     */
    protected function flash()
    {
        $data = [];
        foreach ($this->inputs as $input) {
            $data[$input->getName()] = [
                'value' => $input->getFormValue(),
                'error' => $input->getError(),
            ];
        }
        $this->storeState($this->sessionKey, $data);
    }

    /**
     * Retrieve input values and error messages from session
     */
    protected function unflash()
    {
        $data = $this->retrieveState($this->sessionKey);
        foreach ($data as $name => $d) {
            if ($this->hasInput($name)) {
                $this->inputs[$name]->setFormValue($d['value']);
                $this->inputs[$name]->error($d['error']);
            }
        }
    }

    protected function storeState($key, $data)
    {
        @session_start();
        $_SESSION[$key] = $data;
    }

    protected function retrieveState($key)
    {
        @session_start();
        if (isset($_SESSION[$key])) {
            $out = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $out;
        } else {
            return [];
        }
    }

    protected function clearState($key)
    {
        @session_start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    protected function setAttribute($name, $value)
    {
        if ($name == 'method') {
            $this->setMethod($value);
            return;
        }
        if (is_null($value)) {
            if (array_key_exists($name, $this->attributes)) {
                unset($this->attributes[$name]);
            }
        } else {
            $this->attributes[$name] = $value;
        }
    }

    protected function defaultId()
    {
        if (!isset($this->attributes['id'])) {
            $_id = '_' . $this->idSuffix();
            $this->attributes['id'] = "form{$_id}";
            $this->attributes['data-id-suffix'] = $_id;
        }
    }

    protected function idSuffix()
    {
        if ($this->idHash === null) {
            $this->idHash = RandomString::make();
        }
        return $this->idHash;
    }

    protected function setMethod($method)
    {
        $method = strtoupper($method);
        if (in_array($method, $this->spoofableMethods)) {
            $this->spoofMethod = $method;
            $method = 'POST';
        }
        $this->attributes['method'] = $method;
    }

    private function inputOrFail($name)
    {
        $this->lazySetup();
        return $this->inputs[$name];
    }
}
