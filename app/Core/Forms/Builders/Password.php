<?php
/**
 * @package app.treq.pcore
 */

/**
 * A password HTML form input element.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Password extends AbstractBuilder
{

    /**
     * Render an HTML form submit element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $out = '<input type="password" value="' . $this->format($this->value) . '" ' . $this->getAttributesHtml() . ' />';
        return $out;
    }

}
