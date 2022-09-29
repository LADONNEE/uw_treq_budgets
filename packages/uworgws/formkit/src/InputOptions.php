<?php
namespace Uworgws\Formkit;

class InputOptions
{
    private $builtOptions;
    private $options;
    private $optionFactory;
    private $optionsPre = [];
    private $optionsPost = [];

    public function __construct($options = null)
    {
        $this->setOptions($options);
    }

    public function addFirstOption($display, $index)
    {
        $this->builtOptions = null;
        $this->optionsPre[$index] = $display;
    }

    public function addLastOption($display, $index)
    {
        $this->builtOptions = null;
        $this->optionsPost[$index] = $display;
    }

    public function all(): array
    {
        if ($this->builtOptions === null) {
            $this->buildOptions();
        }
        return $this->builtOptions;
    }

    public function hasOptions(): bool
    {
        if ($this->builtOptions === null) {
            $this->buildOptions();
        }
        return count($this->builtOptions) > 0;
    }

    public function setOptions($options)
    {
        $this->builtOptions = null;
        $this->options = null;
        $this->optionFactory = null;

        if (is_callable($options)) {
            $this->optionFactory = $options;
        } elseif(is_array($options)) {
            $this->options = $options;
        }
    }

    private function getOptions(): array
    {
        if ($this->options === null) {
            if (is_callable($this->optionFactory)) {
                $this->options = call_user_func($this->optionFactory);
            }
        }
        return ($this->options) ?: [];
    }

    private function buildOptions()
    {
        $this->builtOptions = array_replace($this->optionsPre, $this->getOptions());
        if ($this->optionsPost) {
            foreach ($this->optionsPost as $key => $value) {
                // if a $key is already defined, move it to the end of the list
                unset($this->builtOptions[$key]);
                $this->builtOptions[$key] = $value;
            }
        }
    }
}
