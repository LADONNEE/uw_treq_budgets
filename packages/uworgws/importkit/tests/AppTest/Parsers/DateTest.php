<?php
namespace Uwcoenvws\Parsers;

use Uwcoenvws\Importkit\Parsers\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{

    public function test_it_returns_integer_timestamp()
    {
        $it = new Date();
        $result = $it->parse('2015-12-25');

        $this->assertTrue(is_numeric($result));
        $this->assertTrue($result > 28800 && $result < 2145945600);
    }

    public function test_it_returns_the_date_value()
    {
        $it = new Date();
        $result = $it->parse('2015-12-25');

        $this->assertSame('12/25/2015', date('n/j/Y', $result));
    }

    public function test_it_returns_the_date_and_time_value()
    {
        $it = new Date();
        $result = $it->parse('2015-12-25 13:11:45');

        $this->assertSame('2015-12-25 13:11:45', date('Y-m-d H:i:s', $result));
    }

    public function test_it_parses_common_representations_of_dates()
    {
        $it = new Date();

        $result = $it->parse('12/25/2015');
        $this->assertSame('2015-12-25', date('Y-m-d', $result));

        $result = $it->parse('Dec 25, 2015');
        $this->assertSame('2015-12-25', date('Y-m-d', $result));

        $result = $it->parse('2013-10-14T00:00:00');
        $this->assertSame('2013-10-14', date('Y-m-d', $result));

    }

}
