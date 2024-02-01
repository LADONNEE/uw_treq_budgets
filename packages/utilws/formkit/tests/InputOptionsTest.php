<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\InputOptions;

class InputOptionsTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new InputOptions();
        $this->assertInstanceOf(InputOptions::class, $it);
    }

    public function test_all_returns_an_array()
    {
        $it = new InputOptions();
        $this->assertIsArray($it->all());
    }

    public function test_it_accepts_options_in_constructor()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $this->assertSame($options, $it->all());
    }

    public function test_it_accepts_options_through_set_options()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions();
        $it->setOptions($options);
        $this->assertSame($options, $it->all());
    }

    public function test_it_adds_an_option_to_front()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $it->addFirstOption('First', '');

        $expect = [
            '' => 'First',
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_adds_multiple_options_to_front()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $it->addFirstOption('First', '');
        $it->addFirstOption('Second', 'pre2');

        $expect = [
            '' => 'First',
            'pre2' => 'Second',
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_adds_an_option_to_end()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $it->addLastOption('Last', '');

        $expect = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            '' => 'Last',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_adds_multiple_options_to_end()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $it->addLastOption('Lastish', 'y');
        $it->addLastOption('Last', 'z');

        $expect = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            'y' => 'Lastish',
            'z' => 'Last',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_adds_options_to_front_and_end()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $it->addFirstOption('First', 'f');
        $it->addLastOption('Last', 'L');

        $expect = [
            'f' => 'First',
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            'L' => 'Last',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_takes_callable_as_option_list()
    {
        $it = new InputOptions(function() {
            return [
                'zero',
                'one',
                'two',
            ];
        });
        $it->addFirstOption('First', 'f');
        $expect = [
            'f' => 'First',
            'zero',
            'one',
            'two',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_has_options_is_false_with_zero_items()
    {
        $it = new InputOptions();
        $this->assertFalse($it->hasOptions());
    }

    public function test_has_options_is_false_with_one_or_more_items()
    {
        $options = [
            'zero',
            'one',
            'two',
        ];
        $it = new InputOptions($options);
        $this->assertTrue($it->hasOptions());

        $it = new InputOptions();
        $it->addFirstOption('First', 'f');
        $this->assertTrue($it->hasOptions());
    }
}
