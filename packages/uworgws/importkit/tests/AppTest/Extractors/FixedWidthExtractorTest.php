<?php
namespace AppTest\Extractors;

use Uwcoenvws\Importkit\Extractors\FixedWidthExtractor;
use PHPUnit\Framework\TestCase;

class FixedWidthExtractorTest extends TestCase
{
    //                       1         2         3         4
    //             01234567890123456789012345678901234567890
    const INPUT = '12345 Yes XMary Lynn    Jones 999-99-9999';

    public function test_it_splits_a_string_by_field_configuration()
    {
        $it = new FixedWidthExtractor([
            '0-4',
            '6-9',
            '11-23',
            '30-40'
        ]);

        $result = $it->extract(self::INPUT);

        $this->assertTrue(is_array($result), 'extract() result is array');
        $this->assertSame(4, count($result), 'result has 4 fields');
        $this->assertSame('Yes ', array_values($result)[1]);
    }

    public function test_field_specification_becomes_array_index()
    {
        $it = new FixedWidthExtractor([
            '0-4',
            '6-9',
            '11-23',
            '30-40'
        ]);

        $result = $it->extract(self::INPUT);

        $this->assertSame('12345', $result['0-4']);
        $this->assertSame('Yes ', $result['6-9']);
        $this->assertSame('Mary Lynn    ', $result['11-23']);
        $this->assertSame('999-99-9999', $result['30-40']);
    }

    public function test_fields_outside_record_length_return_null()
    {
        $it = new FixedWidthExtractor([
            '41-50',
            '999-1024',
        ]);

        $result = $it->extract(self::INPUT);

        $this->assertNull($result['41-50']);
        $this->assertNull($result['999-1024']);
    }

    public function test_fields_beginning_inside_record_length_return_available_part()
    {
        $it = new FixedWidthExtractor([
            '37-100',
        ]);

        $result = $it->extract(self::INPUT);

        $this->assertSame('9999', $result['37-100']);
    }

    public function test_invalid_field_specification_throws_exception()
    {
        $this->expectException(\Exception::class);
        new FixedWidthExtractor([
            '0-4',
            '6-9',
            'bad',
            '37-100',
        ]);
    }

}
