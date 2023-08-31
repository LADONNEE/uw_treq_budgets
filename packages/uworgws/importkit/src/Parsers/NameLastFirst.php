<?php
namespace Uwcoenvws\Importkit\Parsers;

/**
 * Break an aggregate Last, First Middle field into parts and return last name
 * Other fields are available through their own methods
 * Class NameLastFirst
 * @package Uwcoenvws\Importkit\Parsers
 */
class NameLastFirst extends BaseParser
{
    protected $prevParse;
    protected $first;
    protected $last;
    protected $middle;
    protected $initial;

    protected function parseValue($input)
    {
        if ($input === $this->prevParse) {
            return $this->last;
        }

        $this->prevParse = $input;
        $this->first = '';
        $this->last = '';
        $this->middle = '';
        $this->initial = '';
        $fixcase = false;
        $name = trim($input);
        if (strlen($name) == 0) {
            return null;
        }
        // Check if they typed name in all upper or all lower
        $lcname = strtolower($name);
        $ucname = strtoupper($name);
        if ($name === $lcname || $name === $ucname) {
            $fixcase = true;
        }
        if (strpos($input, ',')) {
            $parts = explode(',', $input);
            $this->last = trim($parts[0]);
            if (count($parts) > 1) {
                $parts = explode(' ', trim($parts[1]));
                if (count($parts) > 1) {
                    $this->middle = array_pop($parts);
                    $this->first = implode(' ', $parts);
                } else {
                    $this->first = $parts[0];
                }
            }
        } else {
            $this->last = trim($input);
        }
        if ($fixcase) {
            $this->first  = $this->properCase($this->first);
            $this->last   = $this->properCase($this->last);
            $this->middle = $this->properCase($this->middle);
        }
        $this->initial = substr($this->middle, 0, 1);
        if ($this->first) {
            return $this->last.', '.$this->first;
        }
        return $this->last;
    }

    public function getFirstName()
    {
        return $this->first;
    }

    public function getLastName()
    {
        return $this->last;
    }

    public function getMiddle()
    {
        return $this->middle;
    }

    public function getMiddleInitial()
    {
        return $this->initial;
    }

    public function properCase($value)
    {
        $value = trim($value);
        if (empty($value)) {
            return null;
        }
        $value = strtolower($value);
        $value = str_replace('-', '[-] ', $value);
        $value = str_replace("'", '[apos] ', $value);
        $value = ucwords($value);
        $value = str_replace('[-] ', '-', $value);
        $value = str_replace('[apos] ', "'", $value);
        return $value;
    }

}
