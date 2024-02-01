<?php
namespace Utilws\Importkit\Parsers;

/**
 * Parse last five digits of Social Security Number from input
 * Removes all punctuation and whitespace
 */
class SsnFive extends SsnFour
{
    protected $keep = 5;
}
