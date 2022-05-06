<?php
/**
 * @package edu.uw.uaa.pcore
 */

/**
 * A readonly HTML element.
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Readonly extends AbstractBuilder
{

    /**
     * Render an HTML form text element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $attribs = $this->getAttributesHtml(array('class'));
        if (array_key_exists('class', $this->attributes)) {
            $class = 'read_only_input ' . $this->attributes['class'];
        } else {
            $class = 'read_only_input';
        }
        $out = '<div class="' . $class . '" ' . $attribs . '>' . $this->format($this->value) . '</div>';
        return $out;
    }

}
