<?php
namespace Utilws\Importkit\Parsers;

class Name extends BaseParser
{

    protected function parseValue($input)
	{
		// Check if they typed name in all upper or all lower
		$lcname = strtolower($input);
		$ucname = strtoupper($input);
		if ($input === $lcname || $input === $ucname) {
            $input = ucwords($lcname);
		}
		return $input;
	}

}
