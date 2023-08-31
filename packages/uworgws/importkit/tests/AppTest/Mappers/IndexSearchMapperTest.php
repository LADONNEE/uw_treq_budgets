<?php
namespace AppTest\Mappers;

use Uwcoenvws\Importkit\Mappers\IndexSearchMapper;
use PHPUnit\Framework\TestCase;

class IndexSearchMapperTest extends TestCase
{

    public function test_it_returns_array_with_configured_keys()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 0 ],
            'field_2' => [ 1 ],
            'field_3' => [ 2 ],
        ]);

        $result = $it->map([]);

        $this->assertTrue(is_array($result), 'map() result is array');
        $this->assertSame(3, count($result), 'result has 3 fields');
        $this->assertTrue(array_key_exists('field_1', $result), 'field_1 key exists');
        $this->assertTrue(array_key_exists('field_2', $result), 'field_2 key exists');
        $this->assertTrue(array_key_exists('field_3', $result), 'field_3 key exists');
    }

    public function test_fields_not_found_in_input_have_null_values()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 0 ],
            'field_2' => [ 1 ],
            'field_3' => [ 2 ],
        ]);

        $result = $it->map([]);

        $this->assertNull($result['field_1'], 'field_1 is null');
        $this->assertNull($result['field_2'], 'field_2 is null');
        $this->assertNull($result['field_3'], 'field_3 is null');
    }

    public function test_it_assigns_values_from_mapped_field()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 0 ],
            'field_2' => [ 1 ],
            'field_3' => [ 2 ],
        ]);

        $result = $it->map([
            'value one',
            'value two',
            'value three',
        ]);

        $this->assertSame('value one', $result['field_1']);
        $this->assertSame('value two', $result['field_2']);
        $this->assertSame('value three', $result['field_3']);
    }

    public function test_it_searches_named_indexes()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 'foo' ],
            'field_2' => [ 'bar' ],
            'field_3' => [ 'baz' ],
        ]);

        $result = $it->map([
            'baz' => 'value one',
            'foo' => 'value two',
            'bar' => 'value three',
        ]);

        $this->assertSame('value two', $result['field_1']);
        $this->assertSame('value three', $result['field_2']);
        $this->assertSame('value one', $result['field_3']);
    }

    public function test_it_searches_multiple_indexes_until_it_finds_one_that_is_defined()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 'foo', 'bim', 'bom', 'bez' ],
        ]);

        $result = $it->map([
            'baz' => 'value one',
            'bom' => 'value two',
            'bez' => 'a match, but not first, so not used',
            'bar' => 'value three',
        ]);

        $this->assertSame('value two', $result['field_1']);
    }

    public function test_it_provides_value_from_a_defined_index_with_a_null_value()
    {
        $it = new IndexSearchMapper([
            'field_1' => [ 'foo', 'bim', 'bom', 'bez' ],
        ]);

        $result = $it->map([
            'baz' => 'not searched',
            'bom' => 'a match, but not first, so not used',
            'bez' => 'a match, but not first, so not used',
            'bim' => null, // first searched field has NULL value
        ]);

        $this->assertNull($result['field_1']);
    }

    public function test_single_fields_can_be_specified_without_array()
    {
        $it = new IndexSearchMapper([
            'field_1' => 'bom',
            'field_2' => 'bar',
            'field_3' => 'baz',
        ]);

        $result = $it->map([
            'baz' => 'value one',
            'bom' => 'value two',
            'bez' => 'a match, but not first, so not used',
            'bar' => 'value three',
        ]);

        $this->assertSame('value two', $result['field_1']);

    }

}
