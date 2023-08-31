<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Convert sex values to one letter F or M
 * Class Gender
 * @package Uwcoenvws\Importkit\Parsers
 */
class Gender extends BaseParser
{
	public static $gender_map = array(
		'female' => 'F',
		'male'   => 'M'
	);

    protected function parseValue($input)
	{
	    $input = strtolower($input);
		if (isset(self::$gender_map[$input])) {
			return self::$gender_map[$input];
		}
        return null;
	}

}
