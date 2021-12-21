<?php

namespace App\Console\Commands;

use App\Updaters\Biennium\BienniumDeleteTask;
use Illuminate\Console\Command;

class BienniumDelete extends Command
{
    protected $signature = 'biennium:delete {biennium : 4 digit start year of biennium to delete}';
    protected $description = 'Delete UW Budget records for an old biennium';

    public function handle()
    {
        $biennium = (int) $this->argument('biennium');
        if (!$biennium) {
            echo " Requires argument for biennium to delete. Ex: $ php artisan biennium:delete 2017\n";
            return;
        }

        if (!$this->confirm("! Delete UW Budget metadata for biennium {$biennium}. Continue?")) {
            echo " Not confirmed. Aborting delete with no changes.\n\n";
            return;
        }

        $task = new BienniumDeleteTask($biennium);
        $task->run();

        echo " Completed delete $biennium\n";
    }
}
