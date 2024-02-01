<?php
namespace Utilws\Importkit\Parsers;

/**
 * Parse string value trimmed and lower case
 */
class TextToLower extends BaseParser
{

    protected function parseValue($input)
    {
        return strtolower($input);
    }

}
