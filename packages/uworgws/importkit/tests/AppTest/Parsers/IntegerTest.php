<?php
namespace Uwcoenvws\Parsers;

use Uwcoenvws\Importkit\Parsers\Integer;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{

    public function test_null_input_returns_null()
    {
        $parser = new Integer();
        $this->assertNull($parser->parse(null));
    }

    public function test_empty_input_returns_null()
    {
        $parser = new Integer();
        $this->assertNull($parser->parse(''));
    }

    public function test_text_input_returns_null()
    {
        $parser = new Integer();
        $this->assertNull($parser->parse('foo'));
    }

    public function test_integer_input_returns_integer()
    {
        $parser = new Integer();
        $this->assertSame(99, $parser->parse('99'));
    }

    public function test_parser_ignores_whitespace_padding()
    {
        $parser = new Integer();
        $this->assertSame(99, $parser->parse("      99\n"));
    }

    public function test_parser_ignores_dollar_sign()
    {
        $parser = new Integer();
        $this->assertSame(25, $parser->parse("$25"));
    }

    public function test_parser_ignores_commas()
    {
        $parser = new Integer();
        $this->assertSame(1234, $parser->parse("1,234"));
    }

}
