<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\ValueHandlers\TextValue;

class TextValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new TextValue();
        $this->assertInstanceOf(TextValue::class, $it);
    }

    public function test_scrub_trims_input()
    {
        $it = new TextValue();
        $this->assertSame('foo', $it->scrub('  foo  '));
    }

    public function test_scrub_strips_tags()
    {
        $it = new TextValue();
        $this->assertSame('foo', $it->scrub('<span onclick="doBadStuff()">foo</span> '));
    }
}
