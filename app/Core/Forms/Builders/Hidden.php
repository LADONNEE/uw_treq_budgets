<?php

/**
 * A hidden HTML form input element.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Hidden extends AbstractBuilder
{

    /**
     * Render an HTML form text element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $out = '<input type="hidden" value="' . $this->format($this->value) . '" ' . $this->getAttributesHtml() . ' />';
        return $out;
    }

}
