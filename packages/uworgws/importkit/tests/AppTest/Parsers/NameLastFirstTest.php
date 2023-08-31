<?php
namespace Uwcoenvws\Parsers;

use Uwcoenvws\Importkit\Parsers\NameLastFirst;
use PHPUnit\Framework\TestCase;

class NameLastFirstTest extends TestCase
{

    public function test_it_parses_last_name()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda     ');
        $this->assertSame('Carter', $parser->getLastName());
    }

    public function test_it_treats_no_comma_as_all_last_name()
    {
        $parser = new NameLastFirst();
        $parser->parse('Douglas Biggs     ');
        $this->assertSame('Douglas Biggs', $parser->getLastName());
        $this->assertSame('', $parser->getFirstName());
    }

    public function test_it_parses_first_name()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda');
        $this->assertSame('Lynda', $parser->getFirstName());
    }

    public function test_it_parses_middle_initial()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda C');
        $this->assertSame('C', $parser->getMiddle());
    }

    public function test_it_trims_middle_initial()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda C');
        $this->assertSame('Lynda', $parser->getFirstName());
    }

    public function test_it_parses_middle_name()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda Cordova');
        $this->assertSame('Cordova', $parser->getMiddle());
    }

    public function test_it_trims_middle_name()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda Cordova');
        $this->assertSame('Lynda', $parser->getFirstName());
    }

    public function test_first_name_takes_additional_words()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda Jean Cordova');
        $this->assertSame('Lynda Jean', $parser->getFirstName());
        $this->assertSame('Cordova', $parser->getMiddle());
    }

    public function test_hyphenated_names_kept_together()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter, Lynda-Jean Cordova-Sue');
        $this->assertSame('Lynda-Jean', $parser->getFirstName());
        $this->assertSame('Cordova-Sue', $parser->getMiddle());
    }

    public function test_names_with_apostrophe_are_maintained()
    {
        $parser = new NameLastFirst();
        $parser->parse("O'Carter, 'Lynda");
        $this->assertSame("'Lynda", $parser->getFirstName());
        $this->assertSame("O'Carter", $parser->getLastName());
    }

    public function test_space_around_comma_is_not_required()
    {
        $parser = new NameLastFirst();
        $parser->parse('Carter,Lynda');
        $this->assertSame('Lynda', $parser->getFirstName());
        $this->assertSame('Carter', $parser->getLastName());
    }

    public function test_it_converts_all_upper_to_proper_case()
    {
        $parser = new NameLastFirst();
        $parser->parse('CARTER, LYNDA');
        $this->assertSame('Lynda', $parser->getFirstName());
        $this->assertSame('Carter', $parser->getLastName());
    }

    public function test_it_converts_all_lower_to_proper_case()
    {
        $parser = new NameLastFirst();
        $parser->parse('el-carter, lynda');
        $this->assertSame('Lynda', $parser->getFirstName());
        $this->assertSame('El-Carter', $parser->getLastName());
    }

    public function test_it_leaves_mixed_case_as_is()
    {
        $parser = new NameLastFirst();
        $parser->parse('CarTer, LynDa');
        $this->assertSame('LynDa', $parser->getFirstName());
        $this->assertSame('CarTer', $parser->getLastName());
    }

}
