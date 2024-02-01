<?php
/**
 * @package app.treq.pcore
 */

/**
 * A list of radio HTML form input elements.
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class Radio extends AbstractBuilder
{
    protected $valueIsArray = false;
    protected $requiresValueList = true;

    /**
     * Render list of HTML form radio elements
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $n = "\n";
        $t = "\t";
        // prevent putting the same id in multiple places
        $attribs = $this->getAttributesHtml(array('id'));
        // give id a local shortname (strip out dangerous characters)
        $id = preg_replace('/[^a-zA-Z0-9_]/', '', $input->getId());
        $out = $this->liststart;
        foreach ($this->valuelist as $value => $display) {
            $thisid = $id . preg_replace('/[^a-zA-Z0-9]/', '', $value);
            if ($value == $this->value && (strlen($value) == strlen($this->value))) {
                $checked = 'checked="checked" ';
            } else {
                $checked = '';
            }
            $out .= $n . $t . $t . $t . $this->listitemstart
                . '<label for="' . $thisid . '" class="checkbox-label">'
                . '<input type="radio" value="' . htmlspecialchars($value) . '" id="' . $thisid
                . '" ' . $checked . $attribs . ' /> '
                . $this->format($display) . '</label>' . $this->listitemend;
        }
        return $out . $n . $t . $t . $this->listend;
    }

}
