<?php
namespace AppTest;

use Uwcoenvws\Importkit\ConfiguredImportSource;
use Uwcoenvws\Importkit\Readers\CsvFileReader;
use Uwcoenvws\Importkit\Readers\FileReader;
use PHPUnit\Framework\TestCase;

class ConfiguredImportSourceTest extends TestCase
{

    public function test_it_composes_fixed_width_import_from_configuration()
    {
        $config = include __DIR__ . '/../../demo/fixedwidth-configured-import.php';
        $reader = new FileReader(__DIR__ . '/../../demo/fixedwidth-file.txt');
        $it = new ConfiguredImportSource($reader, $config);

        $result = [];
        foreach ($it as $item) {
            $result[] = $item;
        }

        $expect = [
            [   'id' => 7,   'name' => 'Wilma',    'container' => 'carton'    ],
            [   'id' => 19,  'name' => 'Fred',     'container' => 'bag'       ],
            [   'id' => 17,  'name' => 'Betty',    'container' => 'envelope'  ],
            [   'id' => 12,  'name' => 'Barney',   'container' => 'crate'     ],
            [   'id' => 45,  'name' => 'Bam Bam',  'container' => 'pot'       ],
        ];
        $this->assertSame($expect, $result);
    }

    public function test_it_composes_indexed_import_from_configuration()
    {
        $config = include __DIR__ . '/../../demo/configured-import.php';
        $reader = new CsvFileReader(__DIR__ . '/../../demo/csv-file.csv');
        $it = new ConfiguredImportSource($reader, $config);

        $result = [];
        foreach ($it as $item) {
            $result[] = $item;
        }

        $expect = [
            [   'id' => null, 'name' => 'Name',         'balance' => null  ],
            [   'id' => null, 'name' => null,           'balance' => null  ],
            [   'id' => 3,    'name' => 'Jeff',         'balance' => 2100  ],
            [   'id' => 7,    'name' => 'Jones, Mary',  'balance' => 9912  ],
            [   'id' => 2,    'name' => 'Wendel',       'balance' => 5001  ],
            [   'id' => 56,   'name' => 'Kevin',        'balance' => 8     ],
        ];
        $this->assertSame($expect, $result);
    }

}
