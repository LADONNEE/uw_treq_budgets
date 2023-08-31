<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Parse a string values from UW EDW ODS JobTitle fields
 * Removes text in parentheses
 */
class OdsJobTitle extends BaseParser
{

    protected function parseValue($input)
    {
        $par = strpos($input, '(');
        if ($par) {
            $input = substr($input,0, $par);
        }
        $value = trim($input);
        if (strlen($value) === 0) {
            $this->emptyValue();
        }
        return $value;
    }

}
