<?php
namespace Uwcoenvws\Importkit\Parsers;

class Date extends BaseParser
{

    protected function parseValue($input)
	{
        $ts = strtotime($input);
        if ($ts === false) {
            return $this->invalid('Could not parse date');
        }
        return $ts;
	}

}
