<?php
namespace Utilws\Parsers;

use Utilws\Importkit\Exceptions\InvalidParserInputException;
use Utilws\Importkit\Parsers\CourseSplit;
use PHPUnit\Framework\TestCase;

class CourseSplitTest extends TestCase
{

    public function testParseReturnsStdClassOnSuccess()
    {
        $parser = new CourseSplit();
        $this->assertInstanceOf(\stdClass::class, $parser->parse('EDUC 101'));
    }

    public function testParseReturnsNullForEmptyValues()
    {
        $parser = new CourseSplit();
        $this->assertNull($parser->parse(null));
        $parser = new CourseSplit();
        $this->assertNull($parser->parse(''));
    }

    public function test_throws_exception_on_invalid_input()
    {
        $parser = new CourseSplit();

        $this->expectException(InvalidParserInputException::class);

        $parser->parse('99999');
    }

    public function testParsesCurriculumValue()
    {
        $parser = new CourseSplit();
        $result = $parser->parse('EDUC 101');
        $this->assertSame($result->curriculum, 'EDUC');
    }

    public function testParsesCourseNumberValue()
    {
        $parser = new CourseSplit();
        $result = $parser->parse('EDUC 101');
        $this->assertSame($result->courseno, '101');
    }

    public function testParsesCurriculumWithSpace()
    {
        $parser = new CourseSplit();
        $result = $parser->parse('ECFS O 200');
        $this->assertSame($result->curriculum, 'ECFS O');
    }

    public function testParsesCurriculumWithAmpersand()
    {
        $parser = new CourseSplit();
        $result = $parser->parse('EDC&I 401');
        $this->assertSame($result->curriculum, 'EDC&I');
    }

    public function test_throws_exception_when_curriculum_over_six_letters()
    {
        $parser = new CourseSplit();

        $this->expectException(InvalidParserInputException::class);

        $parser->parse('ABCDEFG 200');
    }

}
