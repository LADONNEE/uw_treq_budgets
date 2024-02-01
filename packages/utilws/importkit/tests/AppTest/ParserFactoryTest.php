<?php
namespace AppTest;

require_once __DIR__ . '/MockParser.php';

use Utilws\Importkit\ParserFactory;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{

    public function test_it_returns_a_callable()
    {
        $it = new ParserFactory();

        $function = $it->make('trim');
        $class = $it->make(MockParser::class);
        $closure = $it->make(function() {
            return 'closure result';
        });

        $this->assertTrue(is_callable($function), 'function based parser is callable');
        $this->assertTrue(is_callable($class), 'class based parser is callable');
        $this->assertTrue(is_callable($closure), 'closure based parser is callable');
    }

    public function test_it_throws_exception_on_unknown_parser()
    {
        $it = new ParserFactory();
        $this->expectException(\Exception::class);
        $it->make('not-defined');
    }

    public function test_if_returns_single_instance_of_parser_objects()
    {
        $it = new ParserFactory();

        $first = $it->make(MockParser::class);
        $second = $it->make(MockParser::class);

        $this->assertSame($first, $second, 'multiple make calls for same class provide single instance');

        $first[0]->id = 5;
        $this->assertSame(5, $second[0]->id);
    }

}
