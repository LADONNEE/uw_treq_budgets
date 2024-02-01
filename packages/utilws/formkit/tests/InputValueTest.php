<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\InputValue;

class InputValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new InputValue(new \Utilws\Formkit\ValueHandlers\TextValue());
        $this->assertInstanceOf(InputValue::class, $it);
    }
}
