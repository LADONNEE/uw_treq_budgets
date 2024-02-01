<?php
/**
 * Example of a configured import for an indexed source
 *
 * - Top level array keys (id, name, balance) are the outputted array keys
 * - 'index' provides input record indexes to search for a value
 * - 'parser' provides a parsing strategy for the field
 */

use Utilws\Importkit\Parsers as Parse;

return [
    'id' => [
        'index' => [ 0 ],
        'parser' => Parse\Integer::class
    ],
    'name' => [
        'index' => [ 1 ],
        'parser' => Parse\Text::class
    ],
    'balance' => [
        'index' => [ 2 ],
        'parser' => Parse\Integer::class
    ],
];
