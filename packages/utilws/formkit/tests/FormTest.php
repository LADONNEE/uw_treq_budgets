<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Form;
use Utilws\Formkit\Input;
use Utilws\Formkit\InputView;

class FormTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new \MockForm();
        $this->assertInstanceOf(Form::class, $it);
    }

    public function test_it_provides_inputs_by_name()
    {
        $it = new \MockForm();

        $retrievedInput1 = $it->input('input1');
        $retrievedInput2 = $it->input('input2');

        $this->assertSame($it->input1, $retrievedInput1);
        $this->assertSame($it->input2, $retrievedInput2);
    }

    public function test_add_provides_fluent_factory_for_inputs()
    {
        $it = new \MockForm();

        $input = $it->add('input3');
        $this->assertInstanceOf(Input::class, $input);
    }

    public function test_adding_an_input_to_form_generates_an_id()
    {
        $it = new \MockForm();
        $vars = $it->input('input1')->getInputView()->vars();

        $this->assertArrayHasKey('id', $vars);
        $this->assertRegExp('/^input1_/', $vars['id']);
    }

    public function test_fill_method_sets_model_values_on_inputs()
    {
        $it = new \MockForm();
        $it->add('input3', 'text', new \TestableValueHandler());
        $it->fill([
            'input1' => 'foo',
            'input2' => 'bar',
            'input3' => 'baz',
        ]);

        $this->assertSame('foo', $it->input('input1')->getModelValue());
        $this->assertSame('baz', $it->input('input3')->getModelValue());
        $this->assertSame('baz:form', $it->input('input3')->getFormValue());
    }

    public function test_fill_ignores_extra_fields()
    {
        $it = new \MockForm();
        $it->fill([
            'junk' => 'xyz',
            'input1' => 'foo',
            'input2' => 'bar',
            'other' => 'abc',
        ]);

        $this->assertSame('foo', $it->input('input1')->getModelValue());
        $this->assertSame('bar', $it->input('input2')->getModelValue());
    }

    public function test_fill_user_input_method_sets_model_values_on_inputs()
    {
        $it = new \MockForm();
        $it->add('input3', 'text', new \TestableValueHandler());
        $it->fillUserInput([
            'input1' => 'foo',
            'input2' => 'bar',
            'input3' => 'baz',
        ]);

        $this->assertSame('foo', $it->input('input1')->getFormValue());
        $this->assertSame('baz', $it->input('input3')->getFormValue());
        $this->assertSame('baz:model', $it->input('input3')->getModelValue());
    }

    public function test_fill_user_input_ignores_extra_fields()
    {
        $it = new \MockForm();
        $it->fillUserInput([
            'junk' => 'xyz',
            'input1' => 'foo',
            'input2' => 'bar',
            'other' => 'abc',
        ]);

        $this->assertSame('foo', $it->input('input1')->getFormValue());
        $this->assertSame('bar', $it->input('input2')->getFormValue());
    }

    public function test_process_runs_validate_and_commit()
    {
        $it = new \MockForm();
        $it->process([]);

        $this->assertTrue($it->validateWasRun);
        $this->assertTrue($it->commitWasRun);
    }

    public function test_process_does_not_run_commit_if_any_input_has_error_message()
    {
        $it = new \MockForm();
        $it->input1->error('an error');
        $it->process([]);

        $this->assertTrue($it->validateWasRun);
        $this->assertFalse($it->commitWasRun);
    }

    public function test_it_sets_the_model_value_of_specific_input()
    {
        $it = new \MockForm();
        $it->set('input1', 'set model value');

        $this->assertSame('set model value', $it->input1->getModelValue());
    }

    public function test_it_returns_the_model_value_of_specific_input()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('expected model value');

        $this->assertSame('expected model value', $it->get('input1'));
    }

    public function test_it_sets_the_form_value_of_specific_input()
    {
        $it = new \MockForm();
        $it->setFormValue('input1', 'set form value');

        $this->assertSame('set form value', $it->input1->getFormValue());
    }

    public function test_it_returns_the_form_value_of_specific_input()
    {
        $it = new \MockForm();
        $it->input1->setFormValue('expected form value');

        $this->assertSame('expected form value', $it->formValue('input1'));
    }

    public function test_it_tests_if_specific_input_is_empty()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('non-empty model value');
        $it->input2->setModelValue(null);

        $this->assertFalse($it->isEmpty('input1'));
        $this->assertTrue($it->isEmpty('input2'));

        $it = new \MockForm();
        $it->input1->setModelValue(0);
        $it->input2->setModelValue('');

        $this->assertFalse($it->isEmpty('input1'));
        $this->assertTrue($it->isEmpty('input2'));
    }

    public function test_it_tests_if_specific_input_is_empty_array_value()
    {
        $it = new \MockForm();
        $it->input1->setModelValue(['one']);
        $it->input2->setModelValue([]);

        $this->assertFalse($it->isEmpty('input1'));
        $this->assertTrue($it->isEmpty('input2'));
    }

    public function test_it_returns_all_model_values_as_assoc_array()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');
        $it->input2->setModelValue('World');

        $expect = [
            'input1' => 'Hello',
            'input2' => 'World',
        ];
        $this->assertSame($expect, $it->all());
    }

    public function test_it_returns_only_specified_model_values_as_assoc_array()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');
        $it->input2->setModelValue('World');

        $expect = [
            'input1' => 'Hello',
        ];
        $this->assertSame($expect, $it->only(['input1']));
    }

    public function test_only_can_take_string_argument()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');
        $it->input2->setModelValue('World');

        $expect = [
            'input1' => 'Hello',
        ];
        $this->assertSame($expect, $it->only('input1'));
    }

    public function test_it_returns_model_values_as_assoc_array_without_specified()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');
        $it->input2->setModelValue('World');

        $expect = [
            'input2' => 'World',
        ];
        $this->assertSame($expect, $it->without(['input1']));
    }

    public function test_without_can_take_string_argument()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');
        $it->input2->setModelValue('World');

        $expect = [
            'input2' => 'World',
        ];
        $this->assertSame($expect, $it->without('input1'));
    }

    public function test_it_provides_input_view_objects()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');

        $v = $it->inputView('input1');
        $this->assertInstanceOf(InputView::class, $v);
    }

    public function test_it_allows_local_override_of_input_view_settings()
    {
        $it = new \MockForm();
        $it->input1->setModelValue('Hello');

        $v = $it->inputView('input1', ['id' => 'my_special_id']);
        $vars = $v->vars();
        $this->assertSame('my_special_id', $vars['id']);
    }
}
