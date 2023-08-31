<?php
namespace AppTest;

use Uwcoenvws\Importkit\Parsers\BaseParser;

class MockParser extends BaseParser
{
    public $id;

    public function parseValue($input)
    {
        if ($input === 'invalid') {
            return $this->invalid();
        }
        return 'mock parsed';
    }

}
