<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\InputValue;

class InputValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new InputValue(new \Uworgws\Formkit\ValueHandlers\TextValue());
        $this->assertInstanceOf(InputValue::class, $it);
    }
}
