<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Utilws\Formkit\ValueHandlers\UnixTimeStampValue;

class UnixTimeStampValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new UnixTimeStampValue();
        $this->assertInstanceOf(UnixTimeStampValue::class, $it);
    }

    public function test_it_converts_string_to_unix_timestamp_for_model()
    {
        $it = new UnixTimeStampValue();
        $ts = $it->toModel('2019-09-01 13:15:00');

        $this->assertIsNumeric($ts);
        $this->assertTrue($ts < 1567537200, 'timestamp less than 9/3');
        $this->assertTrue($ts > 1567191600, 'timestamp greater than 8/30');
    }

    public function test_it_converts_unix_timestamp_to_string_for_form()
    {
        $it = new UnixTimeStampValue();
        $septOne = 1567364400;

        $this->assertSame('9/1/2019', $it->fromModelToForm($septOne));
    }

    public function test_string_format_can_be_specified_in_constructor()
    {
        $it = new UnixTimeStampValue('Y-m-d');
        $septOne = 1567364400;

        $this->assertSame('2019-09-01', $it->fromModelToForm($septOne));
    }
}
