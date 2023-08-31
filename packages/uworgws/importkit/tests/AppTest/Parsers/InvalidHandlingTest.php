<?php
namespace Uwcoenvws\Parsers;

require_once __DIR__ . '/../MockParser.php';

use AppTest\MockParser;
use Uwcoenvws\Importkit\Exceptions\InvalidParserInputException;
use PHPUnit\Framework\TestCase;

class InvalidHandlingTest extends TestCase
{
    protected $wasCalled = false;

    public function test_it_throws_exception_on_invalid_input()
    {
        $it = new MockParser();
        $this->expectException(InvalidParserInputException::class);

        $it->parse('invalid');
    }

    public function test_it_calls_invalid_callback_if_provided()
    {
        $this->wasCalled = false;

        $it = new MockParser();
        $it->setInvalidCallback([$this, 'mockCallback']);

        $result = $it->parse('invalid');

        $this->assertNull($result, 'parse returned null');
        $this->assertTrue($this->wasCalled, 'callback was called');
    }

    public function mockCallback($input, $message)
    {
        $this->wasCalled = true;
    }

}
