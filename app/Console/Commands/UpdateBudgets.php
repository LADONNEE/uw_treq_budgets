<?php

/**
 * Console command to update budget data from UW EDW
 * @author hanisko
 */

namespace App\Console\Commands;

use App\Edw\BudgetDataSource;
use App\Updaters\Budget\BudgetUpdateJob;
use App\Updaters\EdwParser;
use Illuminate\Console\Command;

class UpdateBudgets extends Command
{

    protected $signature = 'update:budgets';
    protected $description = 'Update budget data';
    protected $jobClass = 'App\Updaters\Budget\BudgetUpdateJob';

    public function handle(BudgetDataSource $edw, EdwParser $parser)
    {
        $job = new BudgetUpdateJob($edw, $parser);
        $job->run();
    }
}
