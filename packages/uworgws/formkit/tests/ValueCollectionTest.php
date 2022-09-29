<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\ValueCollection;

class ValueCollectionTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new ValueCollection([]);
        $this->assertInstanceOf(ValueCollection::class, $it);
    }

    public function test_it_returns_the_array()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertSame($input, $it->all());
    }

    public function test_it_returns_an_array_with_only_keys_specified()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $expect = [
            'age' => $input['age'],
            'job' => $input['job'],
        ];

        $this->assertSame($expect, $it->only(['age', 'job']));
    }

    public function test_only_accepts_string_and_returns_single_field()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $expect = [
            'age' => $input['age'],
        ];

        $this->assertSame($expect, $it->only('age'));
    }

    public function test_only_returns_empty_array_if_no_keys_match()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertSame([], $it->only(['foo', 'bar', 'baz']));
    }

    public function test_it_returns_an_array_without_keys_specified()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $expect = [
            'name' => $input['name'],
        ];

        $this->assertSame($expect, $it->without(['age', 'job']));
    }

    public function test_without_accepts_string_and_removes_single_field()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $expect = [
            'age' => $input['age'],
            'job' => $input['job'],
        ];

        $this->assertSame($expect, $it->without('name'));
    }

    public function test_without_returns_empty_array_if_all_keys_excluded()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertSame([], $it->without(['name', 'age', 'job']));
    }

    public function test_it_retrieves_one_value_through_get_method()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertSame('Fred', $it->get('name'));
    }

    public function test_get_returns_null_when_field_does_not_exist()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertNull($it->get('foo'));
    }

    public function test_it_provides_fields_as_virtual_properties()
    {
        $input = [
            'name' => 'Fred',
            'age' => 47,
            'job' => 'gravelpit',
        ];
        $it = new ValueCollection($input);

        $this->assertSame('Fred', $it->name);
    }
}
