<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Parsers\EdwGrade;
use PHPUnit\Framework\TestCase;

class EdwGradeTest extends TestCase
{

    public function test_it_returns_first_two_numbers_with_a_decimal()
    {
        $it = new EdwGrade();
        $this->assertSame('4.0', $it->parse('40'));
    }

    public function test_it_return_one_decimal_place_when_more_are_available()
    {
        $it = new EdwGrade();
        $this->assertSame('4.7', $it->parse('475'));
    }

    public function test_it_returns_null_when_input_is_not_two_numbers()
    {
        $it = new EdwGrade();
        $this->assertNull($it->parse('4'));
        $this->assertNull($it->parse('missing'));
    }

}
