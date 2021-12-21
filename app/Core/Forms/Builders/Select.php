<?php
/**
 * @package edu.uw.education.pcore
 */

/**
 * A select HTML form input element.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Select extends AbstractBuilder
{
    protected $valueIsArray = false;
    protected $requiresValueList = true;

    /**
     * Render an HTML form submit element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $n = "\n";
        $t = "\t";
        if (is_array($this->value)) {
            $values = $this->value;
        } else {
            $values = array($this->value);
        }
        $out = '<select ' . $this->getAttributesHtml() . '>';
        foreach ($this->valuelist as $value => $display) {
            $out .= $n . $t . $t . $t;
            if (in_array($value, $values)) {
                $out .= '<option value="' . htmlspecialchars($value) . '" selected="selected">' . $this->format($display) . '</option>';
            } else {
                $out .= '<option value="' . htmlspecialchars($value) . '">' . $this->format($display) . '</option>';
            }
        }
        return $out . $n . $t . $t . '</select>';
    }

}
