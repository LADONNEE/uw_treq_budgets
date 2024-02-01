<?php
namespace AppTest;

require_once __DIR__ . '/../../demo/DemoSkipPs.php';
require_once __DIR__ . '/../../demo/DemoValidateNoPumpkins.php';

use Utilws\Importkit\Extractors\FixedWidthExtractor;
use Utilws\Importkit\ImportSource;
use Utilws\Importkit\Mappers\IndexSearchMapper;
use Utilws\Importkit\ParserLibrary;
use Utilws\Importkit\Readers\ArrayReader;
use PHPUnit\Framework\TestCase;

class ImportSourceTest extends TestCase
{

    public function test_it_can_be_used_in_foreach_loop()
    {
        $expect = [
            'apples',
            'peaches',
            'pumpkin',
            'pie',
        ];
        $reader = new ArrayReader($expect);
        $it = new ImportSource($reader);
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame($expect, $results);
    }

    public function test_it_accepts_skip_helper()
    {
        $reader = new ArrayReader([
            'apples',
            'peaches',
            'pumpkin',
            'pie',
        ]);
        $it = new ImportSource($reader);
        $it->addHelper(new \DemoSkipPs());
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame(1, count($results));
        $this->assertSame('apples', $results[0]);
    }

    public function test_it_accepts_extract_helper()
    {
        $reader = new ArrayReader([
          // 0123456789 12345
            '1 apples  bushel',
            '2 peaches basket',
            '3 pumpkin crate',
            '4 pie     box',
        ]);
        $it = new ImportSource($reader);
        $it->addHelper(new FixedWidthExtractor([
            '0-0',
            '2-9',
            '10-15'
        ]));
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame(4, count($results));
        $this->assertTrue(is_array($results[0]));
        $this->assertSame('basket', $results[1]['10-15']);
    }

    public function test_it_accepts_mapper_helper()
    {
        $reader = new ArrayReader([
            // 0123456789 12345
            '1 apples  bushel',
            '2 peaches basket',
            '3 pumpkin crate',
            '4 pie     box',
        ]);
        $it = new ImportSource($reader);
        $it->addHelper(new FixedWidthExtractor([
            '0-0',
            '2-9',
            '10-15'
        ]));
        $it->addHelper(new IndexSearchMapper([
            'id' => '0-0',
            'fruit' => '2-9',
            'container' => '10-15'
        ]));
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame(4, count($results));
        $this->assertTrue(is_array($results[0]));
        $this->assertSame('1', $results[0]['id']);
        $this->assertSame('peaches ', $results[1]['fruit']);
        $this->assertSame('crate', $results[2]['container']);
    }

    public function test_it_accepts_parser_helper()
    {
        $parsers = new ParserLibrary();
        $parsers->default(function() {
            return 'I was parsed';
        });

        $reader = new ArrayReader([
            ['apples'],
            ['peaches'],
            ['pumpkin'],
            ['pie'],
        ]);

        $it = new ImportSource($reader);
        $it->addHelper($parsers);
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame('I was parsed', $results[0][0]);
        $this->assertSame('I was parsed', $results[1][0]);
        $this->assertSame('I was parsed', $results[2][0]);
        $this->assertSame('I was parsed', $results[3][0]);
    }

    public function test_it_accepts_validator_helper()
    {
        $reader = new ArrayReader([
            'apples',
            'peaches',
            'pumpkin',
            'pie',
        ]);
        $it = new ImportSource($reader);
        $it->addHelper(new \DemoValidateNoPumpkins());
        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $expect = [
            'apples',
            'peaches',
            'pie',
        ];
        $this->assertSame($expect, $results);
    }

    public function test_it_accepts_unordered_helpers_through_constructor()
    {
        $reader = new ArrayReader([
            // 0123456789 12345
            '1 apples  bushel',
            '2 peaches basket',
            '3 pumpkin crate',
            '4 pie     box',
        ]);
        $extractor = new FixedWidthExtractor([
            '0-0',
            '2-9',
            '10-15'
        ]);
        $mapper = new IndexSearchMapper([
            'id' => '0-0',
            'fruit' => '2-9',
            'container' => '10-15'
        ]);
        $parsers = new ParserLibrary();
        $parsers->default(function() {
            return 'I was parsed';
        });

        $it = new ImportSource($reader, $parsers, $extractor, $mapper);

        $results = [];

        foreach ($it as $record) {
            $results[] = $record;
        }

        $this->assertSame(4, count($results));
        $this->assertTrue(is_array($results[0]));
        $this->assertSame('I was parsed', $results[0]['id']);
        $this->assertSame('I was parsed', $results[1]['fruit']);
        $this->assertSame('I was parsed', $results[2]['container']);
    }

}
