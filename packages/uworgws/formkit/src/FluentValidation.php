<?php
namespace Uworgws\Formkit;

use Uworgws\Formkit\Validators\BaseValidator;
use Uworgws\Formkit\Validators\InList;
use Uworgws\Formkit\Validators\NotEmpty;

class FluentValidation
{
    private $input;

    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    public function notEmpty(string $message = 'Can not be empty')
    {
        $this->run(new NotEmpty($message));
        return $this;
    }

    public function inList(string $message = 'Choose an option from this list')
    {
        $this->run(new InList($message));
        return $this;
    }

    public function using(BaseValidator ...$validators)
    {
        foreach ($validators as $validator) {
            $this->run($validator);
        }
        return $this;
    }

    private function run(BaseValidator $validator)
    {
        if ($this->input->hasError()) {
            return;
        }
        $validator->validate($this->input);
    }
}
