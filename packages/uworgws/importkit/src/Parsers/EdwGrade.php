<?php
namespace Uwcoenvws\Importkit\Parsers;

class EdwGrade extends BaseParser
{

    protected function parseValue($input)
    {
        if (preg_match('/\d\d/', $input)) {
            $digits = str_split($input);
            return "{$digits[0]}.{$digits[1]}";
        }
        return $this->emptyValue();
    }

}
