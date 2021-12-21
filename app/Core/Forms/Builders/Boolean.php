<?php
/**
 * @package edu.uw.environment.pcore
 */

/**
 * The Form Input Boolean class generates a single checkbox that can
 * be checked or not. Programmatically generates form element HTML to
 * produce consistent, functional, and scriptable page elements.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Boolean extends AbstractBuilder
{
    /**
     * Render an HTML form text element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        if ($this->value) {
            $checked = ' checked="checked"';
        } else {
            $checked = '';
        }
        if ($input->getBooleanText()) {
            $description = $input->getBooleanText();
        } else {
            $description = $input->getLabel();
        }
        // hidden input provides default value, if checkbox is not check field is not included in payload
        $out = '<label class="checkbox-label">'
            . '<input type="hidden" value="0" name="' . $this->name . '" />'
            . '<input type="checkbox" value="1" ' . $this->getAttributesHtml() . $checked . ' />'
            . $this->format($description)
            . '</label>';
        return $out;
    }

}
