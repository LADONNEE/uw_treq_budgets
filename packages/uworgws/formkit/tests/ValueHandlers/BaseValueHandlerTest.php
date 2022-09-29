<?php
use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\ValueHandlers\BaseValueHandler;

class BaseValueHandlerTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new BaseValueHandler();
        $this->assertInstanceOf(BaseValueHandler::class, $it);
    }

    public function test_it_tests_null_as_empty()
    {
        $it = new BaseValueHandler();
        $this->assertTrue($it->isEmpty(null));
    }

    public function test_it_tests_empty_string_as_empty()
    {
        $it = new BaseValueHandler();
        $this->assertTrue($it->isEmpty(''));
    }

    public function test_it_tests_string_as_not_empty()
    {
        $it = new BaseValueHandler();
        $this->assertFalse($it->isEmpty('foo'));
    }

    public function test_it_tests_zero_as_not_empty()
    {
        $it = new BaseValueHandler();
        $this->assertFalse($it->isEmpty(0));
    }

    public function test_it_tests_boolean_false_as_not_empty()
    {
        $it = new BaseValueHandler();
        $this->assertFalse($it->isEmpty(false));
    }

    public function test_scrub_returns_the_input()
    {
        $it = new BaseValueHandler();
        $this->assertSame('foo', $it->scrub('foo'));
        $this->assertSame('', $it->scrub(''));
    }

    public function test_to_model_returns_the_input()
    {
        $it = new BaseValueHandler();
        $this->assertSame('foo', $it->toModel('foo'));
        $this->assertSame('', $it->toModel(''));
    }

    public function test_from_model_to_form_returns_the_input()
    {
        $it = new BaseValueHandler();
        $this->assertSame('foo', $it->fromModelToForm('foo'));
        $this->assertSame('', $it->fromModelToForm(''));
    }
}
