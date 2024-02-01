<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Input;

class InputMaxLengthTest extends TestCase
{
    private $it;

    public function setUp(): void
    {
        $this->it = new Input('foo');
    }

    public function test_maxlength_default_is_null()
    {
        $this->assertNull($this->it->getProperty('maxlength'));
    }

    public function test_maxlength_setter_stores_maxlength()
    {
        $this->it->maxlength(100);

        $this->assertSame(100, $this->it->getProperty('maxlength'));
    }

    public function test_set_attribute_method_stores_maxlength()
    {
        $this->it->set('maxlength', 100);

        $this->assertSame(100, $this->it->getProperty('maxlength'));
    }

    public function test_maxlength_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class, $this->it->maxlength(10));
    }

    public function test_html_maxlength_is_not_included_as_htmlAttribute_when_not_set()
    {
        $vars = $this->it->getInputView()->vars();

        $this->assertNotRegExp('/\bmaxlength=/', $vars['htmlAttributes']);
    }

    public function test_maxlength_sets_maxlength_on_input_view_htmlAttributes()
    {
        $this->it->maxlength(10);
        $vars = $this->it->getInputView()->vars();

        $this->assertRegExp('/\bmaxlength="10"/', $vars['htmlAttributes']);
    }

    public function test_get_model_value_returns_full_string_with_no_max_length()
    {
        $this->it->setUserInput('0123456789_Not Part of the Answer');

        $this->assertSame('0123456789_Not Part of the Answer', $this->it->getModelValue());
    }

    public function test_get_model_value_is_truncated_to_maxlength()
    {
        $this->it->maxlength(10);
        $this->it->setUserInput('0123456789_Not Part of the Answer');

        $this->assertSame('0123456789', $this->it->getModelValue());
    }
}
