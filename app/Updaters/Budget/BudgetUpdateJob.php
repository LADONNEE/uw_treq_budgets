<?php

namespace App\Updaters\Budget;

use App\Edw\BudgetDataSource;
use App\Updaters\EdwParser;

class BudgetUpdateJob
{
    /**
     * @var BudgetDataSource
     */
    protected $edw;

    /**
     * @var EdwParser
     */
    protected $parser;

    public function __construct(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->edw = $edw;
        $this->parser = $parser;
    }

    public function run()
    {
        (new ImportUwBudgetsTask($this->edw, $this->parser))->run();
        (new CreateLocalBudgetsTask(false, $this->parser))->run();
    }
}
