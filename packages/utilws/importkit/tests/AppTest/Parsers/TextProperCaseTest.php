<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Parsers\TextProperCase;
use PHPUnit\Framework\TestCase;

class TextProperCaseTest extends TestCase
{

    public function testConvertsLowerToProperCase()
    {
        $parser = new TextProperCase();
        $this->assertSame('Jane Austen', $parser->parse('jane austen'));
    }

    public function testConvertsUpperToProperCase()
    {
        $parser = new TextProperCase();
        $this->assertSame('Jane Austen', $parser->parse('JANE AUSTEN'));
    }

    public function testTrimsInput()
    {
        $parser = new TextProperCase();
        $this->assertSame('Jane Austen', $parser->parse(' JANE AUSTEN   '));
    }

    public function testConvertsMultipleSpacesToSingle()
    {
        $parser = new TextProperCase();
        $this->assertSame('Jane Austen', $parser->parse('JANE   AUSTEN'));
    }

    public function testLeavesLittleWordsLowerCase()
    {
        $parser = new TextProperCase();
        $this->assertSame('George of the Jungle', $parser->parse('GEORGE OF THE JUNGLE'));
    }

    public function testLeavePunctuationInPlace()
    {
        $parser = new TextProperCase();
        $this->assertSame('Before/After. Smith,Adam -Arts & Science', $parser->parse('before/after. SMITH,ADAM -ARTS & SCIENCE'));
    }

}
