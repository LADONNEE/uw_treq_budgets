<?php
namespace Utilws\Readers;

use Utilws\Importkit\Readers\CsvFileReader;
use PHPUnit\Framework\TestCase;

class CsvFileReaderTest extends TestCase
{
    protected $filename = __DIR__ . '/../../../demo/csv-file.csv';


    public function test_it_reads_lines_in_turn()
    {
        $it = new CsvFileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 6; ++ $l) {
            $line = $it->read();
            $result[] = $line[1] ?? null;
        }

        $this->assertSame('Jeff', $result[2]);
        $this->assertSame('Jones, Mary', $result[3]);
        $this->assertSame('Wendel', $result[4]);
        $this->assertSame('Kevin', $result[5]);
    }

    public function test_each_item_read_is_an_array()
    {
        $it = new CsvFileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 6; ++ $l) {
            $result[] = $it->read();
        }

        $this->assertTrue(is_array($result[0]), 'record 0 is an array');
        $this->assertTrue(is_array($result[2]), 'record 2 is an array');
        $this->assertTrue(is_array($result[5]), 'record 5 is an array');
    }

    public function test_it_returns_false_at_end_of_stream()
    {
        $it = new CsvFileReader($this->filename);

        for ($l = 0; $l < 6; ++ $l) {
            $it->read();
        }

        $result = $it->read();

        $this->assertFalse($result, 'returned false on 7th read');
    }

    public function test_it_starts_at_beginning_after_reset()
    {
        $it = new CsvFileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 4; ++ $l) {
            $line = $it->read();
            $result[] = $line[1] ?? null;
        }
        $this->assertSame('Name', $result[0]);
        $this->assertSame('Jeff', $result[2]);

        $it->reset();
        $line = $it->read();
        $result = $line[1] ?? null;

        $this->assertSame('Name', $result);
    }

}
