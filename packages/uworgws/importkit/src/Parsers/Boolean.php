<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Convert common bit representations to boolean
 * @package Uwcoenvws\Importkit
 */
class Boolean extends BaseParser
{
	protected static $false_values = array(
		'FALSE',
		'NO',
		'N',
		'0'
	);
	
	protected function parseValue($input)
	{
        $input = strtoupper($input);
		if (in_array($input, self::$false_values) || $input === 0) {
			return false;
		}
        return true;
	}

}
