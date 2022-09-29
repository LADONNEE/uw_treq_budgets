<?php
namespace Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\ValueHandlers\CarbonDateValue;

class CarbonDateValueTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new CarbonDateValue();
        $this->assertInstanceOf(CarbonDateValue::class, $it);
    }

    public function test_it_converts_string_to_carbon_for_model()
    {
        $it = new CarbonDateValue();
        $cd = $it->toModel('2019-09-01 13:15:00');

        $this->assertInstanceOf(Carbon::class, $cd);
        $this->assertSame('9/1/2019', $cd->format('n/j/Y'));
    }

    public function test_it_converts_carbon_to_string_for_form()
    {
        $it = new CarbonDateValue();
        $cd = new Carbon('2019-09-01 13:15:00');

        $this->assertSame('9/1/2019', $it->fromModelToForm($cd));
    }
}
