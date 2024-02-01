<?php
namespace Utilws\Importkit\Parsers;

/**
 * Parse last four digits of Social Security Number from input
 * Removes all punctuation and whitespace
 */
class SsnFour extends BaseParser
{
    protected $keep = 4;

    protected function parseValue($input)
    {
        $val = preg_replace('/[^0-9]/', '', $input);
        $length = strlen($val);
        if ($length > $this->keep) {
            $val = substr($val, ($length - $this->keep));
        }
        if (strlen($val) == $this->keep) {
            return $val;
        }
        return null;
    }

}
