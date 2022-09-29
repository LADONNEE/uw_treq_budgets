<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\Input;
use Uworgws\Formkit\InputView;

class InputViewTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new InputView(new Input('foo'));
        $this->assertInstanceOf(InputView::class, $it);
    }

    public function test_vars_returns_an_array()
    {
        $it = new InputView(new Input('foo'));
        $this->assertIsArray($it->vars());
    }

    public function test_vars_array_provides_view_data()
    {
        $it = new InputView(new Input('foo'));
        $vars = $it->vars();

        $this->assertArrayHasKey('view', $vars);
        $this->assertArrayHasKey('name', $vars);
        $this->assertArrayHasKey('type', $vars);
        $this->assertArrayHasKey('value', $vars);
        $this->assertArrayHasKey('options', $vars);
        $this->assertArrayHasKey('error', $vars);
        $this->assertArrayHasKey('label', $vars);
        $this->assertArrayHasKey('id', $vars);
        $this->assertArrayHasKey('class', $vars);
        $this->assertArrayHasKey('required', $vars);
        $this->assertArrayHasKey('help', $vars);
        $this->assertArrayHasKey('helpId', $vars);
        $this->assertArrayHasKey('booleanText', $vars);
        $this->assertArrayHasKey('htmlAttributes', $vars);
    }

    public function it_provides_default_view_based_on_input_type()
    {
        $it = new InputView(new Input('foo', 'bar'));
        $vars = $it->vars();

        $this->assertSame('inputs.bar', $vars['view']);
    }

    public function it_provides_name_from_input()
    {
        $it = new InputView(new Input('foo'));
        $vars = $it->vars();

        $this->assertSame('foo', $vars['name']);
    }

    public function it_provides_type_from_input()
    {
        $it = new InputView(new Input('foo', 'other'));
        $vars = $it->vars();

        $this->assertSame('other', $vars['type']);
    }

    public function it_provides_form_value_from_input()
    {
        $input = new Input('foo');
        $input->setFormValue('Hello World');

        $it = new InputView($input);
        $vars = $it->vars();

        $this->assertSame('Hello World', $vars['value']);
    }

    public function it_provides_options_from_input()
    {
        $options = [
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
        ];
        $input = new Input('foo');
        $input->options($options);

        $it = new InputView($input);
        $vars = $it->vars();

        $this->assertSame($options, $vars['options']);
    }

    public function it_provides_error_from_input()
    {
        $input = new Input('foo');
        $it = new InputView($input);
        $vars = $it->vars();

        $this->assertNull($vars['error']);

        $input = new Input('foo');
        $input->error('Something is wrong');
        $it = new InputView($input);
        $vars = $it->vars();

        $this->assertSame('Something is wrong', $vars['error']);
    }

    public function it_stores_and_provides_label()
    {
        $it = new InputView(new Input('foo'));
        $it->set('label', 'My Label');
        $vars = $it->vars();

        $this->assertSame('My Label', $vars['label']);
    }

    public function it_generates_default_label_from_input_name()
    {
        $it = new InputView(new Input('foo'));
        $vars = $it->vars();

        $this->assertSame('Foo', $vars['label']);
    }

    public function it_stores_and_provides_id()
    {
        $it = new InputView(new Input('foo'));
        $it->set('id', 'my_id');
        $vars = $it->vars();

        $this->assertSame('my_id', $vars['id']);
    }

    public function it_stores_and_provides_class()
    {
        $it = new InputView(new Input('foo'));
        $it->set('class', 'input-pretty');
        $vars = $it->vars();

        $this->assertSame('input-pretty', $vars['class']);
    }

    public function it_stores_and_provides_boolean_text()
    {
        $it = new InputView(new Input('foo'));
        $it->set('booleanText', 'Check this box');
        $vars = $it->vars();

        $this->assertSame('Check this box', $vars['booleanText']);
    }

    public function it_stores_and_provides_type()
    {
        $it = new InputView(new Input('foo'));
        $it->set('type', 'select');
        $vars = $it->vars();

        $this->assertSame('select', $vars['type']);
    }

    public function it_stores_and_provides_help()
    {
        $it = new InputView(new Input('foo'));
        $it->set('help', 'Make every input count!');
        $vars = $it->vars();

        $this->assertSame('Make every input count!', $vars['help']);
    }

    public function it_stores_and_provides_required_as_bool()
    {
        $it = new InputView(new Input('foo'));
        $vars = $it->vars();

        $this->assertFalse($vars['required']);

        $it = new InputView(new Input('foo'));
        $it->set('required', 1);
        $vars = $it->vars();

        $this->assertTrue($vars['required']);
    }

    public function test_helpid_is_empty_by_default()
    {
        $it = new InputView(new Input('foo'));
        $vars = $it->vars();

        $this->assertEmpty($vars['helpId']);
    }

    public function test_if_help_is_set_helpid_is_provided()
    {
        $it = new InputView(new Input('foo'));
        $it->set('help', 'Make every input count!');
        $vars = $it->vars();

        $this->assertNotEmpty($vars['helpId']);
    }

    public function test_it_provides_html_attributes_string()
    {
        $it = new InputView(new Input('foo'));
        $it->set('rows', 10);
        $vars = $it->vars();

        $this->assertNotEmpty($vars['htmlAttributes']);
    }

    public function test_html_attributes_string_does_not_include_id()
    {
        $it = new InputView(new Input('foo'));
        $it->set('id', 'my_id');
        $vars = $it->vars();

        $this->assertNotRegExp('/\bid="[^"]+"/', $vars['htmlAttributes']);
    }

    public function test_html_attributes_string_does_not_includes_class()
    {
        $it = new InputView(new Input('foo'));
        $it->set('class', 'input-pretty');
        $vars = $it->vars();

        $this->assertNotRegExp('/\bclass="[^"]+"/', $vars['htmlAttributes']);
    }

    public function test_any_additional_properties_are_added_to_html_attributes()
    {
        $it = new InputView(new Input('foo'));
        $it->set('rows', 10);
        $it->set('data-foo', 'bar');
        $vars = $it->vars();

        $this->assertRegExp('/\brows="10"/', $vars['htmlAttributes']);
        $this->assertRegExp('/\bdata-foo="bar"/', $vars['htmlAttributes']);
    }

    public function test_vars_from_view_can_be_overridden_during_build()
    {
        $it = new InputView(new Input('foo'));
        $it->set('label', 'My Label');
        $it->buildVars(['label' => 'Other Label']);
        $vars = $it->vars();

        $this->assertSame('Other Label', $vars['label']);
    }

    public function test_no_argument_for_buildvars_uses_label_configured_for_input()
    {
        $input = new Input('foo');
        $input->label('Expected');

        $it = $input->getInputView();
        $it->buildVars();
        $vars = $it->vars();

        $this->assertSame('Expected', $vars['label']);
    }

    public function test_passing_empty_string_to_buildvars_sets_empty_label()
    {
        $input = new Input('foo');
        $input->label('Expected');

        $it = $input->getInputView();
        $it->buildVars('');
        $vars = $it->vars();

        $this->assertSame('', $vars['label']);
    }

    public function test_vars_help_can_be_overridden_during_build()
    {
        $it = new InputView(new Input('foo'));
        $it->set('help', 'Hello');
        $it->buildVars(['help' => 'World']);
        $vars = $it->vars();

        $this->assertSame('World', $vars['help']);
    }

    public function test_vars_id_can_be_overridden_during_build()
    {
        $it = new InputView(new Input('foo'));
        $it->set('id', 'original_id');
        $it->buildVars(['id' => 'my_special_id']);
        $vars = $it->vars();

        $this->assertSame('my_special_id', $vars['id']);
    }

    public function test_vars_name_can_be_overridden_during_build()
    {
        $it = new InputView(new Input('foo'));
        $it->buildVars(['name' => 'bar']);
        $vars = $it->vars();

        $this->assertSame('bar', $vars['name']);
    }

    public function test_vars_type_can_be_overridden_during_build()
    {
        $it = new InputView(new Input('foo'));
        $it->set('type', 'select');
        $it->buildVars(['type' => 'radio']);
        $vars = $it->vars();

        $this->assertSame('radio', $vars['type']);
    }

    public function test_vars_from_input_can_be_overridden_during_build()
    {
        $input = new Input('foo');
        $input->error('Starting error');

        $it = new InputView($input);

        $it->buildVars(['error' => 'Better error']);
        $vars = $it->vars();

        $this->assertSame('Better error', $vars['error']);
    }

    public function test_html_attributes_can_be_added_during_build()
    {
        $it = new InputView(new Input('foo'));

        $it->buildVars(['data-prop' => 'yes']);
        $vars = $it->vars();

        $this->assertRegExp('/\bdata-prop="yes"/', $vars['htmlAttributes']);
    }

    public function test_html_disabled_attribute_is_converted_to_xhmtl_style()
    {
        $it = new InputView(new Input('foo'));
        $it->set('disabled', true);
        $vars = $it->vars();

        $this->assertRegExp('/\bdisabled="disabled"/', $vars['htmlAttributes']);
    }

    public function test_html_readonly_attribute_is_converted_to_xhmtl_style()
    {
        $it = new InputView(new Input('foo'));
        $it->set('readonly', true);
        $vars = $it->vars();

        $this->assertRegExp('/\breadonly="readonly"/', $vars['htmlAttributes']);
    }

    public function test_html_required_is_included_as_attribute()
    {
        $it = new InputView(new Input('foo'));
        $it->set('required', true);
        $vars = $it->vars();

        $this->assertRegExp('/\brequired="required"/', $vars['htmlAttributes']);
    }
}
