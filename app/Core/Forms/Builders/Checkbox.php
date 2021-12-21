<?php
/**
 * @package edu.uw.education.pcore
 */

/**
 * List of checkbox elements with multiple boxes checkable
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;
use Exception;

class Checkbox extends AbstractBuilder
{
    protected $valueIsArray = true;
    protected $requiresValueList = true;

    /**
     * Render a list of HTML form checkbox elements
     * @param Input $input
     * @return string
     * @throws Exception
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $n = "\n";
        $t = "\t";
        // prevent putting the same id in multiple places, handling name manually to add []
        $attribs = $this->getAttributesHtml(array('id', 'name'));

        // give id a local shortname (strip out dangerous characters)
        $id = preg_replace('/[^a-zA-Z0-9_]/', '', $this->name);

        $out = $n . $t . $t . '<' . trim($this->liststart, '<>') . ' id="' . $id . '">';

        foreach ($this->valuelist as $value => $display) {
            $thisid = $id . preg_replace('/[^a-zA-Z0-9]/', '', $value);
            if (in_array($value, $this->value)) {
                $checked = ' checked="checked"';
            } else {
                $checked = '';
            }
            $out .= $n . $t . $t . $t . $this->listitemstart
                . '<label class="checkbox-label">'
                . '<input type="checkbox" name="' . $this->name . '[]" value="' . htmlspecialchars($value) . '" id="' . $thisid
                . '"' . $checked . ' ' . $attribs . ' /> '
                . $this->format($display) . '</label>' . $this->listitemend;
        }
        return $out . $n . $t . $t . $this->listend;
    }

}
