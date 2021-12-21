<?php


/**
 * Strategy for rendering an input for a select Person type ahead input
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;

class PersonTypeahead extends AbstractBuilder
{
    const CSS_CLASS = 'person-typeahead';

    /**
     * Render an HTML form text element
     * @param Input $input
     * @return string
     */
    public function build(Input $input)
    {
        $this->extract($input);
        $out = sprintf(
            '<input type="hidden" value="%s" %s /><input type="text" id="%s" name="%s" class="%s" placeholder="%s" value="%s" data-for="%s" %s />',
            $this->format($this->value), // hidden value=
            $this->getAttributesHtml(),  // hidden attributes
            $this->widgetId(),    // widget id=
            $this->widgetName(),  // widget name=
            $this->cssClasses(),  // widget class=
            $this->placeholder(), // widget placeholder=
            $this->format($this->currentSearchTerm()), // widget value=
            $this->hiddenId(), // id of hidden input
            $this->getAttributesHtml(['id', 'name', 'class', 'placeholder'])
        );
        return $out;
    }

    public function cssClasses()
    {
        if (isset($this->attributes['class'])) {
            if (strpos($this->attributes['class'], self::CSS_CLASS) === false) {
                return $this->attributes['class'] . ' ' . self::CSS_CLASS;
            }
            return $this->attributes['class'];
        }
        return self::CSS_CLASS;
    }

    public function currentSearchTerm()
    {
        if ($this->value) {
            return eFirstLast($this->value);
        }
        return '';
    }

    public function hiddenId()
    {
        return (isset($this->attributes['id'])) ? $this->attributes['id'] : $this->name;
    }

    public function placeholder()
    {
        if (isset($this->attributes['placeholder'])) {
            return $this->attributes['placeholder'];
        }
        return 'Search by name or NetID';
    }

    public function widgetId()
    {
        return $this->name . '_typeahead';
    }

    public function widgetName()
    {
        return $this->name . '_typeahead';
    }

}
