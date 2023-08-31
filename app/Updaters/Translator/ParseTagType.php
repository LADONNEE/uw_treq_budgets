<?php

namespace App\Updaters\Translator;

use Uwcoenvws\Importkit\Parsers\BaseParser;

class ParseTagType extends BaseParser
{
    protected function parseValue($input)
    {
        return $input === 'Cost Center' ? 'CostCenter' : $input;
    }
}