<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Basic email cleanup that converts to lower case, no validation
 * Class Email
 * @package Uwcoenvws\Importkit\Parsers
 */
class Email extends BaseParser
{

    protected function parseValue($input)
	{
		return strtolower($input);
	}

}
