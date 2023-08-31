<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Parse a full nine digit Social Security Number from input
 * Removes all punctuation and whitespace
 */
class SsnFull extends BaseParser
{

    protected function parseValue($input)
    {
        $val = preg_replace('/[^0-9]/', '', $input);
        if (strlen($val) == 9) {
            return $val;
        }
        return null;
    }

}
