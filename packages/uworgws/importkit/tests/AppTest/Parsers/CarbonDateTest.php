<?php
namespace Uwcoenvws\Parsers;

use Carbon\Carbon;
use Uwcoenvws\Importkit\Parsers\CarbonDate;
use PHPUnit\Framework\TestCase;

class CarbonDateTest extends TestCase
{

    public function test_it_returns_carbon_instance()
    {
        $it = new CarbonDate();
        $result = $it->parse('2015-12-25');

        $this->assertInstanceOf(Carbon::class, $result);
    }

    public function test_it_returns_the_date_value()
    {
        $it = new CarbonDate();
        $result = $it->parse('2015-12-25');

        $this->assertSame('12/25/2015', $result->format('n/j/Y'));
    }

    public function test_it_returns_the_date_and_time_value()
    {
        $it = new CarbonDate();
        $result = $it->parse('2015-12-25 13:11:45');

        $this->assertSame('2015-12-25 13:11:45', $result->format('Y-m-d H:i:s'));
    }

    public function test_it_parses_common_representations_of_dates()
    {
        $it = new CarbonDate();

        $result = $it->parse('12/25/2015');
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertSame('2015-12-25', $result->format('Y-m-d'));

        $result = $it->parse('Dec 25, 2015');
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertSame('2015-12-25', $result->format('Y-m-d'));

        $result = $it->parse('2013-10-14T00:00:00');
        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertSame('2013-10-14', $result->format('Y-m-d'));

    }

}
