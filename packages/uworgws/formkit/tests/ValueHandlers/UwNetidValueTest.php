<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\ValueHandlers\UwNetidValue;

class UwNetidValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new UwNetidValue();
        $this->assertInstanceOf(UwNetidValue::class, $it);
    }

    public function test_it_strips_email_part_off_email_input()
    {
        $it = new UwNetidValue();
        $this->assertSame('nbedani', $it->scrub('nbedani@uw.edu'));
        $this->assertSame('nbedani', $it->scrub('nbedani@u.washington.edu'));
    }

    public function test_it_converts_netid_to_lower_case()
    {
        $it = new UwNetidValue();
        $this->assertSame('nbedani', $it->scrub('Nbedani'));
        $this->assertSame('boss123', $it->scrub('BOSS123'));
    }
}
