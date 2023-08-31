<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Parse a numeric value with optional decimal places from string input
 */
class Number extends BaseParser
{

    protected function parseValue($input)
	{
		return (float) $input;
	}

}
