<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Parse a string values with outer whitespace trimmed from string input
 */
class Text extends BaseParser
{

    protected function parseValue($input)
	{
		return $input;
	}
	
}
