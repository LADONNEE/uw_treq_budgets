<?php
namespace Uwcoenvws\Importkit;

use Uwcoenvws\Importkit\Exceptions\UnknownParserException;

class ParserLibrary
{
    /**
     * @var callable
     */
    protected $defaultParser = null;

    /**
     * @var callable[]
     */
    protected $parsers = [];

    public function parse($name, $input)
    {
        $parser = $this->get($name);
        return call_user_func($parser, $input);
    }

    public function parseRow(array $row)
    {
        if ($this->defaultParser) {
            return $this->parseAll($row);
        }
        return $this->parseDefined($row);
    }

    public function add($name, callable $parser)
    {
        $this->parsers[$name] = $parser;
    }

    public function get($name)
    {
        if (isset($this->parsers[$name])) {
            return $this->parsers[$name];
        }

        if (is_callable($this->defaultParser)) {
            return $this->defaultParser;
        }

        throw new UnknownParserException($name);
    }

    public function default(callable $parser)
    {
        $this->defaultParser = $parser;
    }

    protected function parseAll(array $row)
    {
        $out = [];
        foreach ($row as $key => $value) {
            $out[$key] = $this->parse($key, $value);
        }
        return $out;
    }

    protected function parseDefined(array $row)
    {
        foreach ($this->parsers as $name => $parser) {
            if (isset($row[$name])) {
                $row[$name] = call_user_func($parser, $row[$name]);
            }
        }
        return $row;
    }

}
