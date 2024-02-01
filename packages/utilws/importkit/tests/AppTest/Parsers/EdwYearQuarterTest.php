<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Parsers\EdwYearQuarter;
use PHPUnit\Framework\TestCase;

class EdwYearQuarterTest extends TestCase
{

    public function test_it_returns_integer_value()
    {
        $parser = new EdwYearQuarter();
        $this->assertSame(2008, $parser->parse('2008'));

        $this->assertSame(4, $parser->parse('4'));
    }

    public function test_null_input_returns_null()
    {
        $parser = new EdwYearQuarter();
        $this->assertNull($parser->parse(null));
    }

    public function test_empty_input_returns_null()
    {
        $parser = new EdwYearQuarter();
        $this->assertNull($parser->parse(''));
    }

    public function test_text_input_returns_null()
    {
        $parser = new EdwYearQuarter();
        $this->assertNull($parser->parse('foo'));
    }

    public function test_zero_input_returns_null()
    {
        $parser = new EdwYearQuarter();
        $this->assertNull($parser->parse('0'));
    }

    public function test_parser_ignores_whitespace_padding()
    {
        $parser = new EdwYearQuarter();
        $this->assertSame(2012, $parser->parse("      2012\n"));
    }

}
