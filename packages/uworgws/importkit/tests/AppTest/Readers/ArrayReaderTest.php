<?php
namespace Uwcoenvws\Readers;

use Uwcoenvws\Importkit\Readers\ArrayReader;
use PHPUnit\Framework\TestCase;

class ArrayReaderTest extends TestCase
{

    public function test_it_reads_each_item_in_turn()
    {
        $it = new ArrayReader([
            'apples',
            'peaches',
            'pumpkin',
            'pie'
        ]);

        $result = [];
        for ($l = 0; $l < 4; ++ $l) {
            $result[] = $it->read();
        }

        $this->assertSame('apples', $result[0]);
        $this->assertSame('pumpkin', $result[2]);
        $this->assertSame('pie', $result[3]);
    }


    public function test_it_returns_false_at_end_of_stream()
    {
        $it = new ArrayReader([
            'apples',
            'peaches',
            'pumpkin',
            'pie'
        ]);

        for ($l = 0; $l < 4; ++ $l) {
            $it->read();
        }

        $result = $it->read();

        $this->assertFalse($result, 'returned false on 5th read');
    }

    public function test_it_starts_at_beginning_after_reset()
    {
        $it = new ArrayReader([
            'apples',
            'peaches',
            'pumpkin',
            'pie'
        ]);

        $result = [];
        for ($l = 0; $l < 3; ++ $l) {
            $result[] = $it->read();
        }
        $this->assertSame('apples', $result[0]);
        $this->assertSame('pumpkin', $result[2]);

        $it->reset();
        $result = $it->read();

        $this->assertSame('apples', $result);
    }

}
