<?php
namespace Utilws\Importkit\Parsers;

class TextProperCase extends BaseParser
{
    private $littleWords = [
        'a', 'an', 'and', 'at',
        'but', 'by',
        'for', 'from',
        'of', 'on',
        'the', 'to'
    ];

    private $cache = [];

    protected function parseValue($input)
    {
        if (isset($this->cache[$input])) {
            return $this->cache[$input];
        }
        $s = preg_replace('/\s\s+/', ' ', $input);
        $words = preg_split('/\b/', $s);
        $out = [];
        foreach ($words as $word) {
            if ($word === '') {
                continue;
            }
            if (preg_match('/^\s*$/', $word)) {
                $out[] = ' ';
                continue;
            }
            $word = strtolower($word);
            if (in_array($word, $this->littleWords)) {
                $out[] = $word;
            } else {
                $out[] = ucfirst($word);
            }
        }
        $this->cache[$input] = implode('', $out);
        return $this->cache[$input];
    }


}
