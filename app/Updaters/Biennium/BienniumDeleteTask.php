<?php

namespace App\Updaters\Biennium;

use App\Models\UwBudget;
use Illuminate\Support\Facades\DB;

class BienniumDeleteTask
{
    private $biennium;

    public function __construct($biennium)
    {
        $this->biennium = $biennium;
    }

    public function run()
    {
        UwBudget::where('BienniumYear', $this->biennium)->delete();
    }

    /**
     * Pruning orphan data is a nice idea, but this is not a safe approach
     *
     * We have set up the Allocation data model so that fiscal can create allocations
     * against other unit's budgets. This creates a COENV `budgets` record with no
     * matching UW `uw_budgets_cache` record and these are required for their
     * related allocation records.
     */
    private function deleteOrphanedBugets()
    {
        // College budget records orphaned when biennium was deleted
        $sql = 'DELETE b.*'
            . ' FROM budgets b'
            . ' LEFT OUTER JOIN uw_budgets_cache uw'
            . ' ON uw.budgetno = b.budgetno'
            . ' WHERE uw.id IS NULL';

        DB::statement($sql);
    }
}
