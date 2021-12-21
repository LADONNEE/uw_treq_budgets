<?php

/**
 * A file HTML form input element.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;
use Exception;

class File extends AbstractBuilder
{

    /**
     * Render an HTML form submit element
     * @param Input $input
     * @return string
     * @throws Exception
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $out = '<input type="file" ' . $this->getAttributesHtml() . ' />';
        return $out;
    }

}
