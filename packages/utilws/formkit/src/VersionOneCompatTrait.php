<?php
namespace Utilws\FormKit;

use Utilws\FormKit\ValueHandlers\BaseValueHandler;

trait FormVersionOneCompatTrait
{
    /**
     * Instantiate new Input, add to collection, and return the Input instance
     * @param string $name
     * @param string $label
     * @param string $type
     * @param BaseValueHandler|null $valueHandler
     * @return Input
     */
    public function add($name, $label = '', $type = 'text', ?BaseValueHandler $valueHandler = null)
    {
        $input = new Input($name, $type, $valueHandler);
        $input->set('label', $label);
        $this->addInput($input);
        return $input;
    }

    /**
     * Return an Input object from a mixed argument
     * Argument may be the Input itself or the string name/index of the input
     * @param mixed $input
     * @return Input
     * @throws \Exception
     */
    public function toInput($input)
    {
        if ($input instanceof Input) {
            return $input;
        }
        if (is_string($input) && $this->hasInput($input)) {
            return $this->input($input);
        }
        throw new \Exception('No input for "' . (string)$input . '"');
    }

    /**
     * Validates all Inputs in collection that have non empty option list with InList
     * @param string $error
     * @throws \Exception
     */
    public function validateAllInList($error = 'Choose a value from the list')
    {
        $this->lazyInit();
        foreach ($this->inputs as $name => $input) {
            if ($input->getOptions()) {
                $this->check($name)->inList($error);
            }
        }
    }

    /**
     * Validates all Inputs in collection that have required=true with validateNotEmpty
     * @param string $error
     */
    public function validateAllRequired($error = 'Cannot be empty')
    {
        foreach ($this->inputs as $name => $input) {
            if ($input->getRequired()) {
                $this->check($name)->notEmpty($error);
            }
        }
    }
    /**
     * Returns $input->getValid()
     * First of
     *  1. NULL if $input has an error
     *  2. parsed if parsed was set for $input
     *  3. value of $input
     * @param mixed $input
     * @return bool
     */
    public function valid($input)
    {
        if ($input instanceof Input) {
            return $input->getModelValue();
        }
        if (isset($this->inputs[$input])) {
            return $this->inputs[$input]->getModelValue();
        }
        return null;
    }

    /**
     * Return the current value of an input
     * Null if no input by $name exists
     * @param mixed $input
     * @return mixed|null
     */
    public function value($input)
    {
        if ($input instanceof Input) {
            return $input->getModelValue();
        }
        if (isset($this->inputs[$input])) {
            return $this->inputs[$input]->getFormValue();
        }
        return null;
    }

    /**
     * Return array of the values of all collection Inputs
     * $without argument is list of input names whose value should NOT be returned
     * @param mixed $without
     * @return array
     */
    public function getValidValues($without = [])
    {
        return $this->getModelValues()->without($without);
    }

    /**
     * Vestigal code, does nothing now
     */
    final public function init()
    {
        // nothing to do
    }

    /**
     * Hook for validations that are selected automatically based on Input properties
     */
    protected function autoValidation()
    {
        $this->validateAllRequired();
        $this->validateAllInList();
    }
}
