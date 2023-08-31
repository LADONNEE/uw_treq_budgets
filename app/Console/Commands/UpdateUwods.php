<?php

namespace App\Console\Commands;

use App\Edw\BudgetDataSource;
use App\Updaters\EdwParser;
use App\Updaters\Worktags\ImportWorktagsJob;

class UpdateUwods extends CommandWithLogging
{
    protected $signature = 'update:uwods';
    protected $description = 'Load Workday financial tags from UWODS database';

    public function handle(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->logToStdOut();
        $job = new ImportWorktagsJob($edw, $parser);
        $job->run();
    }
}
