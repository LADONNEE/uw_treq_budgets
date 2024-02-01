<?php

namespace App\Console\Commands;

use App\Edw\BudgetDataSource;
use App\Updaters\EdwParser;
use App\Updaters\Translator\ImportTranslatorJob;

/**
 * Import Workday worktags to Budget Number mapping from the FDM Translator tool
 */
class ImportTranslator extends CommandWithLogging
{
    protected $signature = 'import:translator {filename : full path to source file, required}';
    protected $description = 'Import Workday worktags to Budget Number mapping from the FDM Translator tool';

    public function handle(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->logToStdOut();
        $job = new ImportTranslatorJob($this->getFilename($this->argument('filename')));
        $job->run();
    }

    private function getFilename($filename): string
    {
        if (file_exists($filename)) {
            return $filename;
        }
        $try = base_path() . '/' . $filename;
        if (file_exists($try)) {
            return $try;
        }
        throw new \Exception("File not found '$filename'");
    }
}
