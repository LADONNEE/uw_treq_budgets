<?php

/**
 * Console command to run once as maintenance to add automatic allocations for existing effort reports
 * @author vinson
 */

namespace App\Console\Commands;

use App\Updaters\Budget\EffortReportAllocationsUpdateJob;
use Illuminate\Console\Command;

class UpdateEffortReportAllocations extends Command
{

    protected $signature = 'update:effort-report-allocations';
    protected $description = 'update effort report allocations for existing Effort Reports to include automatic allocations';

    public function handle()
    {
        $job = new EffortReportAllocationsUpdateJob();
        $job->run();
    }
}
