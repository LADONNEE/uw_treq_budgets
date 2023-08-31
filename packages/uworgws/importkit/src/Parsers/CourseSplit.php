<?php
namespace Uwcoenvws\Importkit\Parsers;

class CourseSplit extends BaseParser
{
    const REGEX_VALID_COURSE = '/^[A-Za-z\&\-\ ]{1,6} \d\d\d$/';

    protected function parseValue($input)
    {
        if (!preg_match(self::REGEX_VALID_COURSE, $input)) {
            return $this->invalid();
        }
        $out = new \stdClass();
        $length = strlen($input);
        $out->curriculum = substr($input, 0, ($length - 4));
        $out->courseno = substr($input, ($length - 3));
        return $out;
    }

}
