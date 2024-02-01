<?php
namespace Utilws\Formkit;

class ValueCollection
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function all()
    {
        return $this->values;
    }

    public function get($name)
    {
        return (isset($this->values[$name])) ? $this->values[$name] : null;
    }

    public function only($keys)
    {
        if (!is_array($keys)) {
            if ($keys === null || $keys === '') {
                $keys = [];
            } else {
                $keys = [ $keys ];
            }
        }
        $out = [];
        foreach ($this->values as $k => $v) {
            if (in_array($k, $keys)) {
                $out[$k] = $v;
            }
        }
        return $out;
    }

    public function without($keys)
    {
        if (!is_array($keys)) {
            if ($keys === null || $keys === '') {
                $keys = [];
            } else {
                $keys = [ $keys ];
            }
        }
        $out = [];
        foreach ($this->values as $k => $v) {
            if (!in_array($k, $keys)) {
                $out[$k] = $v;
            }
        }
        return $out;
    }
}
