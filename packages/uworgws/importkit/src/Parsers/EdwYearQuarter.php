<?php
namespace Uwcoenvws\Importkit\Parsers;

class EdwYearQuarter extends BaseParser
{

    protected function parseValue($input)
    {
        $val = (int) $input;
        return ($val !== 0) ? $val : $this->emptyValue();
    }

}
