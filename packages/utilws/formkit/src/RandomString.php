<?php
namespace Utilws\Formkit;

class RandomString
{
    private $range;

    public function __construct($range = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $this->range = $range;
    }

    public function generate($length = 6)
    {
        $out = [];
        $max = mb_strlen($this->range, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $out[] = $this->range[random_int(0, $max)];
        }
        return implode('', $out);
    }

    public static function make($length = 6)
    {
        $r = new self();
        return $r->generate($length);
    }
}
