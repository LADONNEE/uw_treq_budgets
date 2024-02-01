<?php

use Utilws\Formkit\Input;

class MockForm extends \Utilws\Formkit\Form
{
    public $input1;
    public $input2;

    public $validateWasRun = false;
    public $commitWasRun = false;

    public function __construct()
    {
        $this->input1 = new Input('input1');
        $this->input2 = new Input('input2');
    }

    public function createInputs()
    {
        $this->addInput($this->input1);
        $this->addInput($this->input2);
    }

    public function validate()
    {
        $this->validateWasRun = true;
    }

    public function commit()
    {
        $this->commitWasRun = true;
    }
}
