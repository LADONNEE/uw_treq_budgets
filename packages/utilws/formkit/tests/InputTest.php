<?php
namespace Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Input;
use Utilws\Formkit\InputView;
use Utilws\Formkit\ValueHandlers\CarbonDateValue;

class InputTest extends TestCase
{
    private $it;

    public function setUp(): void
    {
        $this->it = new Input('foo');
    }

    public function test_it_constructs()
    {
        $it = new Input('foo');
        $this->assertInstanceOf(Input::class, $it);
    }

    public function test_it_returns_its_name()
    {
        $this->assertSame('foo', $this->it->getName());
    }

    public function test_it_returns_its_type()
    {
        $this->assertSame('text', $this->it->getType());
    }

    public function test_it_stores_and_returns_its_options()
    {
        $options = [
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
        ];
        $this->it->options($options);

        $this->assertSame($options, $this->it->getOptions());
    }

    public function test_it_can_add_options_to_front()
    {
        $options = [
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
        ];
        $this->it->options($options);
        $this->it->firstOption('Empty');


        $expect = [
            '' => 'Empty',
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
        ];
        $this->assertSame($expect, $this->it->getOptions());
    }

    public function test_it_can_add_options_to_back()
    {
        $options = [
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
        ];
        $this->it->options($options);
        $this->it->lastOption('Empty');


        $expect = [
            'one' => 'Apples',
            'two' => 'Peaches',
            'three' => 'pumpkin',
            '' => 'Empty',
        ];
        $this->assertSame($expect, $this->it->getOptions());
    }

    public function test_it_stores_a_form_value()
    {
        $this->it->setFormValue('Hello');
        $this->assertSame('Hello', $this->it->getFormValue());
    }

    public function test_it_stores_a_model_value()
    {
        $this->it->setModelValue('Hello');
        $this->assertSame('Hello', $this->it->getModelValue());
    }

    public function test_initial_value_sets_the_model_value()
    {
        $this->it->initialValue('Hello');
        $this->assertSame('Hello', $this->it->getModelValue());
    }

    public function test_initial_value_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class,$this->it->initialValue('Hello'));
    }

    public function test_setting_the_model_value_also_sets_the_form_value()
    {
        $this->it->setModelValue('Hello');
        $this->assertSame('Hello', $this->it->getFormValue());
    }

    public function test_setting_the_form_value_also_sets_the_model_value()
    {
        $this->it->setFormValue('Hello');
        $this->assertSame('Hello', $this->it->getModelValue());
    }

    public function test_it_accepts_a_value_handler_argument()
    {
        $it = new Input('foo', 'text', new CarbonDateValue());
        $this->assertInstanceOf(Input::class, $it);
    }

    public function test_value_handler_is_used_to_convert_values()
    {
        $it = new Input('foo', 'text', new CarbonDateValue());
        $it->setFormValue('2/14/2008');

        $this->assertInstanceOf(Carbon::class, $it->getModelValue());
        $this->assertSame('2008-02-14', $it->getModelValue()->format('Y-m-d'));


        $it = new Input('foo', 'text', new CarbonDateValue());
        $it->setModelValue(Carbon::create(2015, 5, 25));

        $this->assertSame('5/25/2015', $it->getFormValue());
    }

    public function test_value_handler_parse_errors_are_store_as_input_error()
    {
        $it = new Input('foo', 'text', new CarbonDateValue());
        $this->assertFalse($it->hasError());

        $it->setFormValue('junk');
        $this->assertTrue($it->hasError());
    }

    public function test_set_user_input_has_same_behavior_as_set_form_value()
    {
        $this->it->setUserInput('Hello');
        $this->assertSame('Hello', $this->it->getFormValue());
    }

    public function test_it_stores_and_returns_error()
    {
        $this->it->error('Buzzz');
        $this->assertSame('Buzzz', $this->it->getError());
    }

    public function test_has_error_is_false_by_default()
    {
        $this->assertFalse($this->it->hasError());
    }

    public function test_has_error_is_true_when_error_is_set()
    {
        $this->it->error('Buzzz');
        $this->assertTrue($this->it->hasError());
    }

    public function test_has_error_is_false_when_error_is_empty()
    {
        $this->it->error('Buzzz');
        $this->assertTrue($this->it->hasError());

        $this->it->error(null);
        $this->assertFalse($this->it->hasError());

        $this->it->error('');
        $this->assertFalse($this->it->hasError());
    }

    public function test_help_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class, $this->it->help('Words'));
    }

    public function test_label_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class, $this->it->label('Words'));
    }

    public function test_required_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class, $this->it->required());
    }

    public function test_it_provides_input_view_instance()
    {
        $this->assertInstanceOf(InputView::class, $this->it->getInputView());
    }

    public function test_providing_id_suffix_sets_id_on_input_view()
    {
        $this->it->idSuffix('qq44nn');
        $vars = $this->it->getInputView()->vars();

        $this->assertSame('foo_qq44nn', $vars['id']);
    }

    public function test_boolean_text_is_fluent_interface()
    {
        $this->assertInstanceOf(Input::class, $this->it->booleanText('I kind of skimmed the terms'));
    }

    public function test_boolean_text_passes_value_to_view()
    {
        $this->it->booleanText('This oxymoronic box is not checked');

        $iv = $this->it->getInputView();

        $this->assertSame('This oxymoronic box is not checked', $iv->get('booleanText'));
    }
}
