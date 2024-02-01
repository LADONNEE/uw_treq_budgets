<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class FormErrorsTest extends TestCase
{
    public function test_get_errors_returns_empty_array()
    {
        $it = new \MockForm();
        $this->assertSame([], $it->getErrors());
    }

    public function test_get_errors_returns_assoc_array_with_input_names_and_error_message()
    {
        $it = new \MockForm();
        $it->input1->error('an error');

        $this->assertSame(['input1' => 'an error'], $it->getErrors());
    }

    public function test_has_errors_returns_false()
    {
        $it = new \MockForm();
        $this->assertFalse($it->hasErrors());
    }

    public function test_has_errors_returns_true_if_any_input_has_an_error_message()
    {
        $it = new \MockForm();
        $it->input1->error('an error');

        $this->assertTrue($it->hasErrors());
    }

    public function test_method_clear_errors_clears_all_existing_errors()
    {
        $it = new \MockForm();
        $it->input1->error('an error');
        $it->input1->error('2nd error');

        $this->assertTrue($it->hasErrors());

        $it->clearErrors();

        $this->assertFalse($it->hasErrors());
        $this->assertSame([], $it->getErrors());
    }

    public function test_filling_new_user_input_clears_all_existing_errors()
    {
        $it = new \MockForm();
        $it->input1->error('an error');

        $this->assertTrue($it->hasErrors());

        $it->fillUserInput(['another_input' => 'irrelevant answer']);

        $this->assertFalse($it->hasErrors());
        $this->assertSame([], $it->getErrors());
    }

    public function test_error_method_sets_an_inputs_error()
    {
        $it = new \MockForm();

        $it->error('input1', 'do not look away from... the nozzle');

        $this->assertSame('do not look away from... the nozzle', $it->input1->getError());
    }

    public function test_error_method_sets_an_inputs_error_to_empty()
    {
        $it = new \MockForm();

        $it->error('input1', 'foo');
        $this->assertSame('foo', $it->input1->getError());

        $it->error('input1', null);
        $this->assertNull($it->input1->getError());
    }

    public function test_get_error_method_returns_an_inputs_empty_error()
    {
        $it = new \MockForm();

        $this->assertNull($it->getError('input1'));
    }

    public function test_get_error_method_returns_an_inputs_error()
    {
        $it = new \MockForm();
        $it->input1->error('an error');

        $this->assertSame('an error', $it->getError('input1'));
    }
}
