<?php
/**
 * @package edu.uw.education.pcore
 */

/**
 * A select HTML element set up to allow multiple items to be selected.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class SelectMultiple extends AbstractBuilder
{
    protected $valueIsArray = true;
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
        $out = '<select multiple="multiple" name="' . $this->name . '[]" ' . $this->getAttributesHtml(['name']) . '>';
        foreach ($this->valuelist as $value => $display) {
            $out .= $n . $t . $t . $t;
            if (in_array($value, $this->value)) {
                $out .= '<option value="' . htmlspecialchars($value) . '" selected="selected">' . $this->format($display) . '</option>';
            } else {
                $out .= '<option value="' . htmlspecialchars($value) . '">' . $this->format($display) . '</option>';
            }
        }
        return $out . $n . $t . $t . '</select>';
    }

}
