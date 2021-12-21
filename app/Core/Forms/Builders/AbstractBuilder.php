<?php

/**
 * Shared routines for classes that can build HTML inputs
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

abstract class AbstractBuilder
{
    // Hints for extract() validation and normalizing values
    protected $valueIsArray = false;
    protected $requiresValueList = false;

    protected $attributes;
    protected $formatter;
    protected $name;
    protected $value;
    protected $valuelist;

    protected $liststart = '<ul>';
    protected $listend = '</ul>';
    protected $listitemstart = '<li>';
    protected $listitemend = '</li>';

    abstract function build(Input $input);

    /**
     * Apply the configured callable to format and escape the form value
     * @param $value
     * @return mixed|string
     */
    public function format($value)
    {
        if (is_callable($this->formatter)) {
            return call_user_func($this->formatter, $value);
        }
        return htmlspecialchars($value);
    }

    /**
     * Validate and extract the input settings needed to render as HTML
     * @param Input $input
     * @throws \Exception
     */
    public function extract(Input $input)
    {
        $this->attributes = $input->getAttributes();
        $this->formatter = $input->getFormat();
        $this->name = $input->getName();
        $this->value = $input->getValue();
        if ($this->requiresValueList) {
            $this->valuelist = $input->getOptions();
            if (empty($this->valuelist) || !is_array($this->valuelist)) {
                throw new \Exception(get_class($this) . ' requires a value list to be rendered');
            }
        }
        if ($this->valueIsArray && !is_array($this->value)) {
            $this->value = [$this->value];
        }
    }

    /**
     * Create the HTML representation of attributes for provided input
     * The optional $skip argument provides an array list of HTML attributes that should
     * not be rendered.
     * @param array|boolean $skip
     * @return string
     */
    public function getAttributesHtml($skip = false)
    {
        if (!$skip) {
            $skip = array();
        }
        $out = array();
        if (!in_array('name', $skip)) {
            $out[] = 'name="' . htmlspecialchars($this->name) . '"';
        }
        if (!in_array('id', $skip) && !isset($this->attributes['id'])) {
            $out[] = 'id="' . htmlspecialchars($this->name) . '"';
        }
        foreach ($this->attributes as $attrib => $value) {
            if (!in_array($attrib, $skip)) {
                $out[] = htmlspecialchars($attrib) . '="' . htmlspecialchars($value) . '"';
            }
        }
        return implode(' ', $out);
    }

}
