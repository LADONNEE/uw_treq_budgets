<?php
namespace Utilws\Importkit;

use Utilws\Importkit\Contracts\ParserContract;

class ParserFactory
{
    private $instances;

    public function make($parser)
    {
        if (is_callable($parser)) {
            return $parser;
        }

        if (is_string($parser)) {

            if (isset($this->instances[$parser])) {
                return $this->instances[$parser];
            }

            if (class_exists($parser)) {
                $instance = new $parser;
                if ($instance instanceof ParserContract || method_exists($instance, 'parse')) {
                    $this->instances[$parser] = [$instance, 'parse'];
                } else {
                    $this->instances[$parser] = [$instance, '__invoke'];
                }
                return $this->instances[$parser];
            }
        }

        throw new \Exception("No strategy to make parser '{$parser}'");
    }

}
