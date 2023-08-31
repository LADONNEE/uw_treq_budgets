<?php
namespace AppTest;

require_once __DIR__ . '/MockParser.php';

use Uwcoenvws\Importkit\Exceptions\UnknownParserException;
use Uwcoenvws\Importkit\ParserLibrary;
use PHPUnit\Framework\TestCase;

class ParserLibraryTest extends TestCase
{
    /**
     * @var ParserLibrary
     */
    protected $parsers;
    
    public function setUp(): void
    {
        $this->parsers = new ParserLibrary();
        $this->parsers->add('function', 'trim');
        $this->parsers->add('class', [ new MockParser(), 'parse' ]);
        $this->parsers->add('closure', function($input) {
            return 'closure result';
        });
    }
    
    public function test_it_returns_callables()
    {
        $this->assertTrue(is_callable($this->parsers->get('function')), 'parser "function" is callable');
        $this->assertTrue(is_callable($this->parsers->get('class')), 'parser "class" is callable');
        $this->assertTrue(is_callable($this->parsers->get('closure')), 'parser "closure" is callable');
    }

    public function test_it_executes_parse()
    {
        $this->assertSame('Roger', $this->parsers->parse('function', ' Roger '));
    }

    public function test_it_uses_a_default_strategy()
    {
        $it = new ParserLibrary();
        $it->default(function(){
            return 'default parser';
        });

        $this->assertSame('default parser', $it->parse('not-defined', 'Any old input'));
    }

    public function test_it_throws_unknown_parser_exception()
    {
        $this->parsers = new ParserLibrary();
        $this->expectException(UnknownParserException::class);
        $this->parsers->parse('not-defined', 'Any old input');
    }

    public function test_it_parses_a_collection_of_fields_by_name()
    {
        $input = [
            'function' => '  trimmed  ',
            'class' => 'will be replaced',
            'closure' => 'will be replaced',
            'other' => 'still the same',
        ];
        $expect = [
            'function' => 'trimmed',
            'class' => 'mock parsed',
            'closure' => 'closure result',
            'other' => 'still the same',
        ];

        $this->assertSame($expect, $this->parsers->parseRow($input));
    }

    public function test_default_parser_is_used_for_any_fields_that_dont_have_a_parser()
    {
        $it = new ParserLibrary();
        $it->add('closure', function(){
            return 'SPECIFIC PARSER';
        });
        $it->default(function(){
            return 'default parser';
        });

        $input = [
            'function' => 'will be replaced',
            'class' => 'will be replaced',
            'closure' => 'will be replaced',
            'other' => 'will be replaced',
        ];
        $expect = [
            'function' => 'default parser',
            'class' => 'default parser',
            'closure' => 'SPECIFIC PARSER',
            'other' => 'default parser',
        ];

        $this->assertSame($expect, $it->parseRow($input));
    }

}
