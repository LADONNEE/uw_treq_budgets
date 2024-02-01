<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Input;

class InputGetPropertyTest extends TestCase
{
    private $it;

    public function setUp(): void
    {
        $this->it = new Input('foo');
    }

    public function test_it_provides_input_local_property_error()
    {
        $this->it->error('Bummer');
        $this->assertSame('Bummer', $this->it->getProperty('error'));
    }

    public function test_it_provides_input_local_property_name()
    {
        $this->assertSame('foo', $this->it->getProperty('name'));
    }

    public function test_it_provides_input_local_property_type()
    {
        $this->assertSame('text', $this->it->getProperty('type'));
    }

    public function test_it_provides_input_compiled_options()
    {
        $this->it->options([ 'f' => 'Fred', 'm' => 'Mary' ]);
        $this->it->firstOption('empty');

        $expect = [
            '' => 'empty',
            'f' => 'Fred',
            'm' => 'Mary'
        ];

        $this->assertSame($expect, $this->it->getProperty('options'));
    }

    public function test_it_provides_model_value()
    {
        $this->it->setModelValue('Hello');

        $this->assertSame('Hello', $this->it->getProperty('value'));
    }

    public function test_it_provides_settings_from_input_view_object()
    {
        $this->it->set('maxlength', '100');

        $this->assertSame('100', $this->it->getProperty('maxlength'));
    }
}
