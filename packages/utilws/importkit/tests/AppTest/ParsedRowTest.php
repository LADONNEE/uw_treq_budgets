<?php
namespace AppTest;

use Utilws\Importkit\ParsedRow;
use PHPUnit\Framework\TestCase;

class ParsedRowTest extends TestCase
{
    /**
     * @var ParsedRow
     */
    protected $row;

    public function setUp(): void
    {
        $this->row = new ParsedRow([
            'name' => 'George',
            'age' => 95,
            'city' => 'Milton',
            'extra' => null,
            'flag' => false,
        ]);
    }

    public function test_it_returns_array_data()
    {
        $result = $this->row->toArray();
        $this->assertTrue(is_array($result), '$result is an array');
        $this->assertSame(5, count($result));
        $this->assertSame('George', $result['name']);
    }

    public function test_it_returns_specific_field()
    {
        $this->assertSame('George', $this->row->get('name'));
    }

    public function test_it_provides_magic_method_properties()
    {
        $this->assertSame('George', $this->row->name);
    }

    public function test_it_provides_existence_of_key()
    {
        $this->assertTrue($this->row->has('name'), 'defined key "name" exists');
        $this->assertTrue($this->row->has('extra'), 'defined key "extra", with null value exists');
        $this->assertTrue($this->row->has('flag'), 'defined key "flag", with false value exists');
        $this->assertFalse($this->row->has('hobbies'), 'no key "hobbies" exists');
    }

    public function test_get_returns_null_for_undefined_fields()
    {
        $this->assertNull($this->row->get('foo'));
    }

}
