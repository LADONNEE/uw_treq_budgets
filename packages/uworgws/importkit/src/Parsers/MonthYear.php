<?php
namespace Uwcoenvws\Importkit\Parsers;

use Carbon\Carbon;

/**
 * Return Carbon date value for the first of the month from MM/YYYY values
 * Class MonthYear
 * @package Uwcoenvws\Importkit\Parsers
 */
class MonthYear extends BaseParser
{

    protected function parseValue($input)
    {
        $input = str_replace('/', '/1/', $input);
        $ts = strtotime($input);
        if ($input !== false) {
            return Carbon::createFromTimestamp($ts);
        }
        return null;
    }

}
