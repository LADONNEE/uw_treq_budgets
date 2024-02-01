<?php
namespace Utilws\Importkit\Parsers;

/**
 * Parse a string values from UW EDW ODS JobTitle fields
 * Simplifies specific categories
 */
class OdsJobCategory extends BaseParser
{
    protected $better = [
        'Classified Staff'                      => 'Classified Staff',
        'Contingent Worker'                     => 'Contingent',
        'Graduate Research & Staff Appointees'  => 'Graduate Research',
        'Graduate Teaching Appointees'          => 'Graduate Teaching',
        'Hourly and Other'                      => 'Hourly Staff',
        'Professional Staff & Librarians'       => 'Professional Staff',

    ];

    protected function parseValue($input)
    {
        return $this->better[$input] ?? $input;
    }

}
