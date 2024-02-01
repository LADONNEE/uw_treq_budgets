<?php
namespace Utilws\Importkit\Parsers;

/**
 * Convert to simple integer including removal of $ and ,
 * Class Integer
 * @package Utilws\Importkit\Parsers
 */
class Integer extends BaseParser
{

    protected function parseValue($input)
	{
		if (strpos($input, '$') === 0) {
            $input = substr($input, 1);
		}
        $input = str_replace(',', '', $input);
		if (is_numeric($input)) {
            return (int) $input;
        }
        return $this->emptyValue();
	}

}
