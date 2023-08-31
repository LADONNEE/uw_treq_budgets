<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Parse string value trimmed and UPPER CASE
 */
class TextToUpper extends BaseParser
{

    protected function parseValue($input)
    {
        return strtoupper($input);
    }

}
