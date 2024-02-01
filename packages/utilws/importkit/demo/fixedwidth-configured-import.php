<?php
/**
 * Example of a configured import for a fixed-width source
 *
 * - Top level array keys (id, name, container) are the outputted array keys
 * - 'extract' provides the column range the value is pulled from
 * - 'parser' provides a parsing strategy for the field
 */

use Utilws\Importkit\Parsers as Parse;

return [
    'id' => [
        'extract' => '0-4',
        'parser' => Parse\Integer::class
    ],
    'name' => [
        'extract' => '5-12',
        'parser' => Parse\Text::class
    ],
    'container' => [
        'extract' => '13-22',
        'parser' => Parse\Text::class
    ],
];
