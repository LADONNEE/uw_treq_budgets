<?php

/**
 * A textarea HTML form input element.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Textarea extends AbstractBuilder
{

    /**
     * Render an HTML form textarea element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $out = '<textarea ' . $this->getAttributesHtml() . '>' . $this->format($this->value) . '</textarea>';
        return $out;
    }

}
