<?php
namespace Uwcoenvws\Importkit;

class ParsedRow
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function has($name)
    {
        return isset($this->data[$name]) || array_key_exists($name, $this->data);
    }

    public function toArray()
    {
        return $this->data;
    }

}
