<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\Validators\NotEmpty;

class NotEmptyTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new NotEmpty();
        $this->assertInstanceOf(NotEmpty::class, $it);
    }

    public function test_it_returns_true_if_value_is_string()
    {
        $it = new NotEmpty();
        $this->assertTrue($it->isValid('foo', []));
    }

    public function test_it_returns_true_if_value_is_number()
    {
        $it = new NotEmpty();
        $this->assertTrue($it->isValid(7, []));
    }

    public function test_it_returns_true_if_value_is_zero()
    {
        $it = new NotEmpty();
        $this->assertTrue($it->isValid(0, []));
    }

    public function test_it_returns_false_if_value_is_null()
    {
        $it = new NotEmpty();

        $this->assertFalse($it->isValidNull([]));
    }

    public function test_it_returns_false_if_value_is_empty_string()
    {
        $it = new NotEmpty();
        $this->assertFalse($it->isValid('', []));
    }

    public function test_it_returns_true_for_list_with_values()
    {
        $it = new NotEmpty();

        $this->assertTrue($it->isValidList(['foo'], []), 'list with one value');

        $this->assertTrue($it->isValidList(['hello', '', null], []), 'list with multiple values');
    }

    public function test_it_returns_false_for_empty_list()
    {
        $it = new NotEmpty();

        $this->assertFalse($it->isValidList([], []), '');
    }
}
