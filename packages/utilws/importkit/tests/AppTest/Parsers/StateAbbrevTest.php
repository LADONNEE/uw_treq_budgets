<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Parsers\StateAbbrev;
use PHPUnit\Framework\TestCase;

class StateAbbrevTest extends TestCase
{
    /* @var $parser StateAbbrev */
    protected $parser;

    public function setUp(): void
    {
        $this->parser = new StateAbbrev();
    }

    public function test_empty_input_returns_null()
    {
        $this->assertNull($this->parser->parse(''));
    }

    public function test_returns_null_for_non_letter_abbrev()
    {
        $this->assertNull($this->parser->parse('A3'));
        $this->assertNull($this->parser->parse('99'));
    }

    public function test_returns_null_for_unknown_strings()
    {
        $this->assertNull($this->parser->parse('foo'));
        $this->assertNull($this->parser->parse('Wash'));
    }

    public function test_returns_two_letter_strings()
    {
        $this->assertSame('WA', $this->parser->parse('WA'));
    }

    public function test_upper_cases_two_letter_strings()
    {
        $this->assertSame('NV', $this->parser->parse('nv'));
    }

    public function test_converts_known_states_to_abbrev()
    {
        $this->assertSame('WA', $this->parser->parse('Washington'));
    }

}
