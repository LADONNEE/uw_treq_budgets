<?php

/**
 * General form input builder
 * Creates type specific builders on need
 * @author hanisko
 */

namespace App\Core\Forms\Builders;

use App\Core\Forms\Input;
use Exception;

class InputBuilder
{
    protected $instances = [];
    protected $types = [
        'boolean' => 'App\Core\Forms\Builders\Boolean',
        'checkbox' => 'App\Core\Forms\Builders\Checkbox',
        'file' => 'App\Core\Forms\Builders\File',
        'hidden' => 'App\Core\Forms\Builders\Hidden',
        'password' => 'App\Core\Forms\Builders\Password',
        'person-select' => 'App\Core\Forms\Builders\PersonTypeahead',
        'radio' => 'App\Core\Forms\Builders\Radio',
        'readonly' => 'App\Core\Forms\Builders\Readonly',
        'select' => 'App\Core\Forms\Builders\Select',
        'selectmultiple' => 'App\Core\Forms\Builders\SelectMultiple',
        'submit' => 'App\Core\Forms\Builders\Submit',
        'text' => 'App\Core\Forms\Builders\Text',
        'textarea' => 'App\Core\Forms\Builders\Textarea',
    ];

    public function build(Input $input)
    {
        $builder = $this->get($input->getBuilder());
        return $builder->build($input);
    }

    public function addType($name, $builderClass)
    {
        $this->types[$name] = $builderClass;
    }

    /**
     * @param string $type
     * @return AbstractBuilder
     * @throws Exception
     */
    public function get($type)
    {
        if (!isset($this->instances[$type])) {
            if (isset($this->types[$type])) {
                $class = $this->types[$type];
            } else {
                if (class_exists($type)) {
                    $class = $type;
                } else {
                    throw new Exception('Not configured for Builder "' . $type . '"');
                }
            }
            $this->instances[$type] = new $class;
        }
        return $this->instances[$type];
    }

    public function has($type)
    {
        return isset($this->types[$type]);
    }

}
