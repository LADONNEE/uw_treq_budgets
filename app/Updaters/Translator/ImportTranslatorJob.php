<?php

namespace App\Updaters\Translator;

use App\Models\WorktagsBudgetsFdmTranslator;
use Utilws\Importkit\ConfiguredImportSource;
use Utilws\Importkit\Parsers as Parse;
use Utilws\Importkit\Readers\CsvFileReader;

class ImportTranslatorJob
{
    private $filename;
    private $now;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->now = now();
    }

    public function run()
    {
        $importer = $this->makeImporter();

        foreach ($importer as $row) {
            $this->saveTranslatorRow($row);
        }

        (new WorktagToBudgetLinksTask())->run();
    }

    private function saveTranslatorRow(array $row)
    {
        $t = WorktagsBudgetsFdmTranslator::firstOrNew([
            'tag_type' => $row['tag_type'],
            'workday_code' => $row['workday_code'],
            'budgetno' => $row['budgetno'],
        ]);

        $t->fill($row);
        $t->loaded_at = $this->now;
        $t->save();
    }

    private function makeImporter()
    {
        $config = [
            'system' => [
                'index' => [ 1 ],
                'parser' => Parse\Text::class
            ],
            'tag_type' => [
                'index' => [ 2 ],
                'parser' => ParseTagType::class
            ],
            'workday_code' => [
                'index' => [ 3 ],
                'parser' => Parse\Text::class
            ],
            'workday_name' => [
                'index' => [ 4 ],
                'parser' => Parse\Text::class
            ],
            'mapping_status' => [
                'index' => [ 5 ],
                'parser' => Parse\Text::class
            ],
            'budgetno' => [
                'index' => [ 6 ],
                'parser' => Parse\Text::class
            ],
            'orgcode' => [
                'index' => [ 8 ],
                'parser' => Parse\Integer::class
            ],
        ];

        $reader = new CsvFileReader($this->filename);

        $import = new ConfiguredImportSource($reader, $config);
        $import->addHelper(new TranslatorSkipRule());

        return $import;
    }
}
