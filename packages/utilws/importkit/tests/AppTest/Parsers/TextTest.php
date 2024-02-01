<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Parsers\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{

    public function test_it_returns_null_for_null()
    {
        $it = new Text();
        $this->assertNull($it->parse(null));
    }

    public function test_it_returns_null_for_empty_string()
    {
        $it = new Text();
        $this->assertNull($it->parse(''));
    }

    public function test_it_returns_null_for_only_whitespace()
    {
        $it = new Text();
        $this->assertNull($it->parse("       \n"));
    }

    public function test_it_returns_text_value()
    {
        $it = new Text();
        $this->assertSame('Hello', $it->parse('Hello'));
    }

    public function test_it_trims_text_value()
    {
        $it = new Text();
        $this->assertSame('Hello', $it->parse('     Hello    '));
    }

}
