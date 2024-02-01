<?php
namespace Utilws\Importkit\Parsers;

use Carbon\Carbon;

class CarbonDate extends BaseParser
{

    protected function parseValue($input)
    {
        $ts = strtotime($input);
        if ($ts === false) {
            return $this->invalid('Could not parse date');
        }
        return Carbon::createFromTimestamp($ts);
    }

    protected function isEmpty($input)
    {
        if ($input === 0 || $input === '0000-00-00 00:00:00') {
            return true;
        }
        return parent::isEmpty($input);
    }

}
