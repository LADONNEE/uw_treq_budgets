<?php
namespace Utilws\Readers;

use Utilws\Importkit\Readers\FileReader;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    protected $filename = __DIR__ . '/../../../demo/file.txt';

    public function test_it_reads_lines_in_turn()
    {
        $it = new FileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 9; ++ $l) {
            $result[] = trim($it->read());
        }

        $this->assertSame('Header', $result[0]);
        $this->assertSame('Line 1', $result[2]);
        $this->assertSame('Footer', $result[8]);
    }
    
    public function test_each_item_read_is_a_string()
    {
        $it = new FileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 9; ++ $l) {
            $result[] = trim($it->read());
        }

        $this->assertTrue(is_string($result[0]), 'record 0 is a string');
        $this->assertTrue(is_string($result[3]), 'record 3 is a string');
        $this->assertTrue(is_string($result[8]), 'record 8 is a string');
    }

    public function test_it_returns_false_at_end_of_stream() 
    {
        $it = new FileReader($this->filename);

        for ($l = 0; $l < 9; ++ $l) {
            trim($it->read());
        }

        $result = $it->read();
        
        $this->assertFalse($result, 'returned false on 10th read');
    }
    
    public function test_it_starts_at_beginning_after_reset()
    {
        $it = new FileReader($this->filename);

        $result = [];
        for ($l = 0; $l < 5; ++ $l) {
            $result[] = trim($it->read());
        }
        $this->assertSame('Header', $result[0]);
        $this->assertSame('Line 3', $result[4]);
        
        $it->reset();
        $result = trim($it->read());

        $this->assertSame('Header', $result);
    }
    
}
