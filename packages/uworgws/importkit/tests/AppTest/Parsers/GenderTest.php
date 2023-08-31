<?php
namespace Uwcoenvws\Parsers;

use Uwcoenvws\Importkit\Parsers\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{

    public function test_it_returns_f_for_female()
    {
        $it = new Gender();
        $this->assertSame('F', $it->parse('FEMALE'));
        $this->assertSame('F', $it->parse('female'));
    }

    public function test_it_returns_m_for_male()
    {
        $it = new Gender();
        $this->assertSame('M', $it->parse('MALE'));
        $this->assertSame('M', $it->parse('male'));
    }

    public function test_it_returns_null_for_other_non_empty_inputs()
    {
        $it = new Gender();
        $this->assertNull($it->parse('x'));
        $this->assertNull($it->parse('y'));
        $this->assertNull($it->parse('other'));
    }

}
