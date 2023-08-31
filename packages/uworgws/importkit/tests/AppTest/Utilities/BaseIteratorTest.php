<?php
namespace Uwcoenvws\Readers;

use Uwcoenvws\Importkit\Utilities\BaseIterator;
use PHPUnit\Framework\TestCase;

class BaseIteratorTest extends TestCase
{
    /**
     * @var BaseIterator
     */
    protected $it;

    public function setUp(): void
    {
        $this->it = new BaseIterator([
            'apples',
            'peaches',
            'pumpkin',
            'pie',
        ]);
    }

    public function test_after_rewind_key_is_zero()
    {
        $this->it->rewind();
        $this->assertSame(0, $this->it->key(), 'key was set to 0');
    }

    public function test_it_moves_to_next()
    {
        $this->it->rewind();
        $this->it->next();
        $this->assertSame(1, $this->it->key(), 'key was set to 0');
    }

    public function test_it_rewinds()
    {
        $this->it->rewind();
        $this->it->next();
        $this->assertSame(1, $this->it->key(), 'key was set to 1');
        $this->it->rewind();
        $this->assertSame(0, $this->it->key(), 'key was set to 0');
    }

    public function test_valid_is_true_when_item_exists()
    {
        $this->it->rewind();
        $this->assertTrue($this->it->valid(), '0 item is valid');
        $this->it->next();
        $this->assertTrue($this->it->valid(), '1 item is valid');
        $this->it->next();
        $this->assertTrue($this->it->valid(), '2 item is valid');
        $this->it->next();
        $this->assertTrue($this->it->valid(), '3 item is valid');
    }

    public function test_valid_is_false_at_end_of_list()
    {
        $this->it->rewind();
        $this->it->next();
        $this->it->next();
        $this->it->next();
        $this->it->next();
        $this->assertSame(4, $this->it->key(), 'key was set to 4');
        $this->assertFalse($this->it->valid(), '4 item is not valid');
    }

    public function test_current_returns_current_item()
    {
        $this->it->rewind();
        $this->assertSame('apples', $this->it->current(), 'item 0');
        $this->it->next();
        $this->it->next();
        $this->assertSame('pumpkin', $this->it->current(), 'item 0');
    }

    public function test_it_can_be_used_in_a_foreach_loop()
    {
        $result = '';
        foreach ($this->it as $word) {
            $result .= $word;
        }
        $this->assertSame('applespeachespumpkinpie', $result);
    }

    public function test_it_works_with_empty_list()
    {
        $it = new BaseIterator([]);

        $it->rewind();
        $this->assertFalse($it->valid(), '0 item is not valid');

        $counter = 0;
        foreach ($it as $word) {
            ++ $counter;
        }
        $this->assertSame(0, $counter, '$counter was not incremented');
    }

}
