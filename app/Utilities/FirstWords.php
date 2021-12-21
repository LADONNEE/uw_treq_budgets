<?php

namespace App\Utilities;

class FirstWords
{
    public function getFirstWords($string, $length)
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        $truncatedSymbol = '...';
        $length = $this->lengthAdjustedForTruncatedSymbol($length, $truncatedSymbol);

        $partial = preg_replace('/\s\s+/', ' ', $string);
        $regex = "/^.{1,{$length}}\b/s";
        $matches = [];
        if (preg_match($regex, $partial, $matches)) {
            $partial = trim($matches[0]);
            if (strlen($partial) < strlen($string)) {
                $partial = "{$partial}{$truncatedSymbol}";
            } else {
                $partial = $string;
            }
        }
        return $partial;
    }

    private function lengthAdjustedForTruncatedSymbol($length, $symbol)
    {
        return $length - strlen($symbol);
    }
}
