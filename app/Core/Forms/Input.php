<?php
/**
 * @package edu.uw.environment.pcore
 */

/**
 * Configuration and helpers for HTML inputs
 */

namespace App\Core\Forms;

use Carbon\Carbon;

/**
 * @property array $attributes
 * @property string $builder
 * @property string $error
 * @property callable $format
 * @property string $help
 * @property string $label
 * @property array $makeNull
 * @property string $name
 * @property array|callable $options
 * @property boolean $required
 * @property callable $scrub
 * @property string $type
 * @property string $value
 */
class Input
{
    protected $attributes = [];
    protected $blockWidth = null;
    protected $booleanText;
    protected $builder;
    protected $error;
    protected $format;
    protected $help;
    protected $label;
    protected $makeNull = [''];
    protected $name;
    protected $required = false;
    protected $scrub;
    protected $type;
    protected $value;
    protected $options;
    protected $optionsBuilt;
    protected $optionsPre;
    protected $optionsPost;
    protected $parsed;
    protected $parsedWasSet = false;
    protected $validTs;

    public function __construct($name, $label = null, $type = 'text')
    {
        if (is_array($name)) {
            $this->applySettings($name);
        } else {
            $this->name($name);
            $this->type($type);
            $this->label($label);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'attributes':
                return $this->getAttributes();
                break;
            case 'builder':
                return $this->getBuilder();
                break;
            case 'error':
                return $this->getError();
                break;
            case 'format':
                return $this->getFormat();
                break;
            case 'help':
                return $this->getHelp();
                break;
            case 'label':
                return $this->getLabel();
                break;
            case 'makeNull':
                return $this->getMakeNull();
                break;
            case 'name':
                return $this->getName();
                break;
            case 'options':
                return $this->getOptions();
                break;
            case 'required':
                return $this->getRequired();
                break;
            case 'scrub':
                return $this->getScrub();
                break;
            case 'type':
                return $this->getType();
                break;
            case 'value':
                return $this->getValue();
                break;
            default:
                return null;
                break;
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'attributes':
                $this->attributes($value);
                break;
            case 'builder':
                $this->builder($value);
                break;
            case 'error':
                $this->error($value);
                break;
            case 'format':
                $this->format($value);
                break;
            case 'help':
                $this->help($value);
                break;
            case 'label':
                $this->label($value);
                break;
            case 'makeNull':
                $this->makeNull($value);
                break;
            case 'name':
                $this->name($value);
                break;
            case 'options':
                $this->options($value);
                break;
            case 'required':
                $this->required($value);
                break;
            case 'scrub':
                $this->scrub($value);
                break;
            case 'type':
                $this->type($value);
                break;
            case 'value':
                $this->value($value);
                break;
            default:
                break;
        }
    }

    /**
     * Adds a CSS class to the attributes array
     * Cautiously, verifies that the class name does not already exist
     * Fluent interface
     * @param $class
     * @return $this
     */
    public function addCssClass($class)
    {
        if (isset($this->attributes['class'])) {
            if (preg_match('/\b' . $class . '\b/', $this->attributes['class'])) {
                $class = $this->attributes['class'];
            } else {
                $class = $this->attributes['class'] . ' ' . $class;
            }
        }
        $this->attributes['class'] = $class;
        return $this;
    }

    /**
     * Apply input settings from an array
     * @param $settings
     */
    public function applySettings($settings)
    {
        foreach ($settings as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    /**
     * Set array of HTML attributes of this input
     * Fluent interface
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes)
    {
        $this->attributes = $attributes;
        if (isset($attributes['id'])) {
            $this->id($attributes['id']);
        }
        return $this;
    }

    /**
     * Set a width for the HTML block this input is rendered in
     * Optionally handled by the view generation system
     * Fluent interface
     * @param integer $width
     * @return $this
     */
    function blockWidth($width)
    {
        $this->blockWidth = (int)$width;
        return $this;
    }

    /**
     * Set the extra text that describe function of a checkbox for a "boolean" input type
     * Used in HTML input generation
     * Fluent interface
     * @param string $text
     * @return $this
     */
    public function booleanText($text)
    {
        $this->booleanText = $text;
        return $this;
    }

    /**
     * Provide a Builder class name that can render this input as HTML
     * Fluent interface
     * @param string $classname
     * @return $this
     */
    public function builder($classname)
    {
        $this->builder = $classname;
        return $this;
    }

    /**
     * Return the value of this input as a system date object
     * If system uses Carbon date, returns Carbon instance, otherwise Unix timestamp integer
     * @return int|null|Carbon
     */
    public function dateOrNull()
    {
        if (empty($this->value)) {
            return null;
        }
        $ts = strtotime($this->value);
        if ($ts === false) {
            return null;
        }
        if (function_exists('dateToCarbon')) {
            return Carbon::createFromTimestamp($ts);
        }
        return $ts;
    }

    /**
     * Uses the configured format routine to sanitize input and returns the sanitized value
     * @param string $value
     * @return string
     */
    public function doFormat($value)
    {
        $format = $this->getFormat();
        return call_user_func($format, $value);
    }

    /**
     * Uses the configured scrub routine to sanitize input and returns the sanitized value
     * @param string $value
     * @return string
     */
    public function doScrub($value)
    {
        $scrub = $this->getScrub();
        return call_user_func($scrub, $value);
    }

    /**
     * Set the string description of this Input's error condition or null
     * Fluent interface
     * @param string|null $error
     * @return $this
     */
    public function error($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Provide a value to be added above the $options list
     * Fluent interface
     * @param string $display
     * @param string $index
     * @return $this
     */
    public function firstOption($display, $index = '')
    {
        $this->optionsBuilt = null;
        if (!is_array($this->optionsPre)) {
            $this->optionsPre = array();
        }
        $this->optionsPre[$index] = $display;
        return $this;
    }

    /**
     * Set a callable that can be used to format the input value
     * Fluent interface
     * @param callable $format
     * @return $this
     */
    public function format(callable $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get the current value of an HTML attribute
     * @param $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    /**
     * Get an associative array of HTML attributes
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * If a blockWidth value was set returns that width in an HTML attribute style="Xpx" tag
     * @return string
     */
    public function getBlockWidthAttrib()
    {
        if ($this->blockWidth) {
            return ' style="width:' . $this->blockWidth . 'px;"';
        }
        return '';
    }

    /**
     * Return the extra text message describing a boolean checkbox
     * @return string
     */
    public function getBooleanText()
    {
        return $this->booleanText;
    }

    /**
     * Return the name of the Builder class that can render this input as HTML
     * If Builder is not defined returns $type property allowing Builder factory to provide a standard implementation
     * @return string
     */
    public function getBuilder()
    {
        if ($this->builder) {
            return $this->builder;
        }
        return $this->type;
    }

    /**
     * Return the callable used to format and escape display values
     * @return callable
     */
    public function getFormat()
    {
        if (!is_callable($this->format)) {
            return [$this, 'defaultFormat'];
        }
        return $this->format;
    }

    /**
     * Return the string description of this input's error condition or null
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Return the help message
     * @return string|null
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Return the HTML id attribute
     * Defaults to same as $name
     * @return mixed
     */
    public function getId()
    {
        if (array_key_exists('id', $this->attributes)) {
            return $this->attributes['id'];
        } else {
            return $this->name;
        }
    }

    /**
     * Return the label text that describes this input
     * @return null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Return array of user input values that should be translated to null
     * @return array
     */
    public function getMakeNull()
    {
        return $this->makeNull;
    }

    /**
     * Return the name attribute
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return an array of options for inputs that have a fixed list of selectable values
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->optionsBuilt)) {
            $this->optionsBuilt = $this->buildOptions();
        }
        return $this->optionsBuilt;
    }

    /**
     * Valid, normalized value parsed from user input
     * @return mixed
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * Returns true if this input value is configured as required field
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Returns a callable that can be used to sanitize user input for this Input instance
     * @return callable
     */
    public function getScrub()
    {
        if (!is_callable($this->scrub)) {
            return [$this, 'defaultScrub'];
        }
        return $this->scrub;
    }

    /**
     * Input state as an structured array
     * @return array
     */
    public function getState()
    {
        $out = [
            'name' => $this->name,
            'value' => $this->value,
            'error' => $this->error,
            'options' => []
        ];
        if ($this->options) {
            $options = $this->buildOptions();
            foreach ($options as $value => $name) {
                $out['options'][] = [
                    'value' => $value,
                    'name' => $name
                ];
            }
        }
        return $out;
    }

    /**
     * Return the input type
     * Input types are strings that map to input builder classes
     * @return bool
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns valid value of this input or null
     * If input has error returns null, if input has a parsed value returns parsed, otherwise returns value
     * @return mixed
     */
    public function getValid()
    {
        if ($this->hasError()) {
            return null;
        }
        if ($this->parsedWasSet) {
            return $this->parsed;
        }
        return $this->value;
    }

    /**
     * Return the value of the input
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns true an error message is stored
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * True if this input has an options list configured
     * @return bool
     */
    public function hasOptions()
    {
        return ($this->options || $this->optionsPre || $this->optionsPost);
    }

    /**
     * True if a parsed value was set on this input
     * @return bool
     */
    public function hasParsed()
    {
        return $this->parsedWasSet;
    }

    /**
     * Help message to be included in HTML generation
     * Fluent interface
     * @param string $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;
        return $this;
    }

    /**
     * Id of the HTML input element
     * Fluent interface
     * @param string $id
     * @return $this
     */
    public function id($id)
    {
        if (is_null($id) || $id === '') {
            unset($this->attributes['id']);
        } else {
            $this->attributes['id'] = $id;
        }
        return $this;
    }

    /**
     * Returns true if the input has an empty equivalent value
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->value === null || $this->value === '' || is_array($this->value) && count($this->value) == 0);
    }

    /**
     * Value for HTML label element
     * Fluent interface
     * @param string $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Provide a value to be added below the $options list
     * Fluent interface
     * @param string $display
     * @param string $index
     * @return $this
     */
    public function lastOption($display, $index = '')
    {
        $this->optionsBuilt = null;
        if (!is_array($this->optionsPost)) {
            $this->optionsPost = array();
        }
        $this->optionsPost[$index] = $display;
        return $this;
    }

    /**
     * Set user input values that should be translated to null
     * Fluent interface
     * @param array $null_equivs
     * @return $this
     */
    public function makeNull(array $null_equivs)
    {
        $this->makeNull = $null_equivs;
        return $this;
    }

    /**
     * Name of input used in HTML name attribute generation
     * Fluent interface
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        if (empty($this->attributes['id'])) {
            $this->id($name);
        }
        return $this;
    }

    /**
     * Set the options that will be used for Input types that have a selectable value list
     * Can be an associative array as the list of value => display name pairs or a callable that
     * generates the option array
     * Fluent interface
     * @param array|callable $options
     * @return $this
     */
    public function options($options)
    {
        $this->optionsBuilt = null;
        $this->options = $options;
        return $this;
    }

    /**
     * Valid, normalized value parsed from user input
     * Fluent interface
     * @param string $value
     * @return mixed
     */
    public function parsed($value)
    {
        $this->parsed = $value;
        $this->parsedWasSet = true;
        return $this;
    }

    /**
     * Set true if this is a required field
     * Provides input for HTML input generation, does automatically add validation
     * Fluent interface
     * @param $required
     * @return $this
     */
    public function required($required)
    {
        $this->required = (boolean)$required;
        return $this;
    }

    /**
     * Set a specific HTML attibutes value without changing others
     * If $value is null attribute is removed
     * Fluent interface
     * @param $name
     * @param $value
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        if ($name == 'id') {
            $this->id($value);
            return $this;
        }
        if (is_null($value)) {
            if (array_key_exists($name, $this->attributes)) {
                unset($this->attributes[$name]);
            }
        } else {
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    /**
     * Provide a suffix shared at form level to create an html id for the input
     * Mildly random string reduces chance of id collision outside of form
     * @param string $suffix
     */
    public function setIdSuffix($suffix)
    {
        if (!isset($this->attributes['id']) || $this->attributes['id'] === $this->name) {
            $this->attributes['id'] = "{$this->name}_{$suffix}";
        }
    }

    /**
     * Applies configured $scrub and converts $makeNull then sets $value
     * If input $value is array sanitizes each value individually
     * @param string $value
     */
    public function setUserInput($value)
    {
        if (is_array($value)) {
            $this->value = array();
            foreach ($value as $v) {
                $this->value[] = $this->processInputScalar($v);
            }
            return;
        }
        $this->value($this->processInputScalar($value));
    }

    /**
     * Set a callable that can be used to sanitize user input for this Input
     * Fluent interface
     * @param callable $scrub
     * @return $this
     */
    public function scrub($scrub)
    {
        $this->scrub = $scrub;
        return $this;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'valueType' => 'text',
            'label' => $this->label,
            'required' => $this->required,
            'value' => $this->value,
            'hasError' => $this->hasError(),
            'error' => $this->error,
            'options' => $this->optionsToArray(),
        ];
    }

    private function optionsToArray()
    {
        $out = [];
        foreach ($this->getOptions() as $value => $label) {
            $out[] = [
                'value' => $value,
                'label' => $label,
            ];
        }
        return $out;
    }

    /**
     * Set the type of HTML Input represented
     * Fluent interface
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns true if input can be converted to Unix timestamp
     * @return boolean
     */
    public function validDate()
    {
        $ts = strtotime($this->value);
        if ($ts !== false) {
            $this->validTs = $ts;
            return true;
        }
        return false;
    }

    /**
     * Assign an unfiltered $value to this Input
     * Fluent interface
     * @param mixed $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        $this->validTs = null;
        return $this;
    }

    /**
     * Checks if the current $value is one of the configured $options
     * If $value is an array returns true only if all of the $values are in $options
     * @return boolean
     */
    public function valueInList()
    {
        $options = $this->getOptions();
        if (is_array($this->value)) {
            foreach ($this->value as $v) {
                if (!isset($options[$v]) && !array_key_exists($v, $options)) {
                    return false;
                }
            }
            return true;
        }
        return (isset($options[$this->value]) || array_key_exists($this->value, $options));
    }

    /**
     * Returns value converted to Unix timestamp
     * @return integer|false
     */
    public function valueTimeStamp()
    {
        if ($this->validTs !== null) {
            return $this->validTs;
        }
        return strtotime($this->value);
    }

    /**
     * Generates the $options list
     * Uses defined $options array or callable to make a base list and add any firstOptions or lastOptions to list
     * @return array
     */
    protected function buildOptions()
    {
        $out = [];
        if ($this->optionsPre) {
            $out = $this->optionsPre;
        }
        if (is_callable($this->options)) {
            $main = call_user_func($this->options);
        } elseif (is_array($this->options)) {
            $main = $this->options;
        } else {
            $main = [];
        }
        $out = array_replace($out, $main);
        if ($this->optionsPost) {
            // if a $key is already defined, move it to the end of the list
            foreach ($this->optionsPost as $key => $value) {
                unset($out[$key]);
            }
            $out = array_replace($out, $this->optionsPost);
        }
        return $out;
    }

    /**
     * Value format routine used if no format is provided in configuration
     * @param $value
     * @return string
     */
    protected function defaultFormat($value)
    {
        return htmlspecialchars($value, ENT_COMPAT | ENT_HTML5, 'UTF-8');
    }

    /**
     * Scrub routine used if no scrub is provided in configuration
     * @param $value
     * @return string
     */
    protected function defaultScrub($value)
    {
        return trim(strip_tags($value));
    }

    /**
     * Run a single scalar unit of input through scrub and makeNull
     * @param $value
     * @return string|null
     */
    protected function processInputScalar($value)
    {
        $value = $this->doScrub($value);
        if (in_array($value, $this->makeNull, true)) {
            $value = null;
        }
        return $value;
    }

    protected function setProperty($property, $value)
    {
        switch ($property) {
            case 'attibutes':
                $this->attributes($value);
                break;
            case 'boolean':
            case 'booleantext':
            case 'boolean-text':
                $this->booleanText($value);
                break;
            case 'format':
            case 'formatter':
                $this->format($value);
                break;
            case 'help':
                $this->help($value);
                break;
            case 'id':
                $this->id($value);
                break;
            case 'name':
                $this->name($value);
                break;
            case 'label':
                $this->label($value);
                break;
            case 'required':
                $this->required($value);
                break;
            case 'type':
                $this->type($value);
                break;
            case 'value':
                $this->value($value);
                break;
            case 'options':
            case 'valuelist':
            case 'value-list':
                $this->options($value);
                break;
            case 'default':
            case 'add-default':
            case 'adddefault':
                $this->firstOption($value);
                break;
            default:
                $this->setAttribute($property, $value);
                break;
        }
    }

}
