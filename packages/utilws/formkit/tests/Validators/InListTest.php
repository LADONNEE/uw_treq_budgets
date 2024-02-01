<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\Validators\InList;

class InListTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new InList();
        $this->assertInstanceOf(InList::class, $it);
    }

    public function test_it_returns_true_if_value_is_in_list()
    {
        $options = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $it = new InList();
        $this->assertTrue($it->isValid(1, $options));
    }

    public function test_it_matches_index_keys_not_label_values()
    {
        $options = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $it = new InList();
        $this->assertFalse($it->isValid('one', $options));
    }

    public function test_it_returns_false_if_value_is_not_in_list()
    {
        $options = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $it = new InList();
        $this->assertFalse($it->isValid(3, $options));
    }

    public function test_it_returns_false_when_list_is_empty()
    {
        $it = new InList();
        $this->assertFalse($it->isValid(1, []));
    }

    public function test_it_validates_list_of_values()
    {
        $options = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $it = new InList();

        $this->assertTrue($it->isValidList([1, 2], $options));
        $this->assertFalse($it->isValidList([1, 3], $options));
    }

    public function test_it_validates_null_value()
    {
        $options = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];
        $it = new InList();

        $this->assertTrue($it->isValidNull($options));
    }
}
