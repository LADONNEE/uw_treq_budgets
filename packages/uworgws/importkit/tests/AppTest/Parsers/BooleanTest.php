<?php
namespace Uwcoenvws\Parsers;

use Uwcoenvws\Importkit\Parsers\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{

    public function test_it_returns_false_for_n()
    {
        $it = new Boolean();
        $this->assertFalse($it->parse('N'));
        $this->assertFalse($it->parse('n'));
    }

    public function test_it_returns_false_for_no()
    {
        $it = new Boolean();
        $this->assertFalse($it->parse('NO'));
        $this->assertFalse($it->parse('no'));
    }

    public function test_it_returns_false_for_string_false()
    {
        $it = new Boolean();
        $this->assertFalse($it->parse('FALSE'));
        $this->assertFalse($it->parse('false'));
    }

    public function test_it_returns_false_for_zero()
    {
        $it = new Boolean();
        $this->assertFalse($it->parse(0));
        $this->assertFalse($it->parse('0'));
    }

    public function test_it_returns_true_for_other_non_empty_inputs()
    {
        $it = new Boolean();
        $this->assertTrue($it->parse('Y'), 'Y evaluates true');
        $this->assertTrue($it->parse('yes'), 'yes evaluates true');
        $this->assertTrue($it->parse('TRUE'), 'TRUE evaluates true');
        $this->assertTrue($it->parse('X'), 'X evaluates true');
        $this->assertTrue($it->parse('FOO'), 'FOO evaluates true');
        $this->assertTrue($it->parse(1), 'integer 1 evaluates true');
        $this->assertTrue($it->parse('1'), 'string 1 evaluates true');
    }

}
