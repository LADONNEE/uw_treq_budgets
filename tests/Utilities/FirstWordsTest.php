<?php

namespace Tests\Utilities;

use App\Utilities\FirstWords;
use Tests\TestCase;

class FirstWordsTest extends TestCase
{
    /*                          1         2         3         4         5         6         7         8         9  */
    /*                 123456789_123456789_123456789_123456789_123456789_123456789_123456789_123456789_123456789_  */
    const STRING_19 = 'The quick brown fox';
    const STRING_44 = 'The quick brown fox jumps over the lazy dog.';
    const STRING_89 = 'The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.';

    private $it;

    public function test_it_instantiates()
    {
        $this->assertInstanceOf(FirstWords::class, $this->it);
    }

    public function test_it_returns_shorter_string_as_is()
    {
        $this->assertSame(self::STRING_19, $this->it->getFirstWords(self::STRING_19, 20));
    }

    public function test_it_truncates_string_to_target_length_or_less()
    {
        $new = $this->it->getFirstWords(self::STRING_44, 38);
        $this->assertTrue(strlen($new) <= 38);
    }

    public function test_truncated_string_ends_with_ellipsis()
    {
        $new = $this->it->getFirstWords(self::STRING_44, 38);
        $foundMatch = (bool) preg_match('/.*\.\.\.$/', $new);
        $this->assertTrue($foundMatch);
    }

    public function test_truncated_string_picks_the_word_break_closest_to_end()
    {
        $new = $this->it->getFirstWords(self::STRING_44, 33);
        $this->assertSame('The quick brown fox jumps over...', $new);
    }

    public function test_the_truncated_string_is_within_limit_counting_ellipsis()
    {
        // 50 character break is first letter of a word, creating longest possible trimmed string
        $new = $this->it->getFirstWords(self::STRING_89, 50);

        $this->assertTrue(strlen($new) <= 50, "String length '$new' is <= 50");
    }

    public function setUp(): void
    {
        $this->it = new FirstWords();
    }
}
