<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Uworgws\Formkit\RandomString;

class RandomStringTest extends TestCase
{
    public function test_it_constructs()
    {
        $it = new RandomString();
        $this->assertInstanceOf(RandomString::class, $it);
    }

    public function test_it_generates_a_string_containing_lowercase_letters_and_numbers()
    {
        $it = new RandomString();

        $this->assertRegExp('/^[a-z0-9]{6}$/', $it->generate());
        $this->assertRegExp('/^[a-z0-9]{6}$/', $it->generate());
        $this->assertRegExp('/^[a-z0-9]{6}$/', $it->generate());
    }

    public function test_string_length_can_be_specified()
    {
        $it = new RandomString();

        $this->assertRegExp('/^[a-z0-9]{10}$/', $it->generate(10));
    }

    public function test_character_set_can_be_specified()
    {
        $it = new RandomString('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $this->assertRegExp('/^[A-Z]{6}$/', $it->generate());
    }

    public function test_it_generates_different_string_accross_a_small_number_calls()
    {
        $it = new RandomString();
        $one = $it->generate();
        $two = $it->generate();
        $three = $it->generate();
        $four = $it->generate();

        $this->assertNotEquals($one, $two);
        $this->assertNotEquals($one, $three);
        $this->assertNotEquals($one, $four);
        $this->assertNotEquals($two, $three);
        $this->assertNotEquals($two, $four);
        $this->assertNotEquals($three, $four);
    }
}
