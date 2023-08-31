<?php

namespace App\Updaters\Translator;

use Illuminate\Support\Facades\DB;

/**
 * Use UWFT FDM Translator data to create relationships between worktags and budget numbers
 */
class WorktagToBudgetLinksTask
{
    public function run()
    {
        $this->createMissingLinks();
        $this->deleteStaleLinks();
    }

    private function createMissingLinks()
    {
        $sql = <<<_SQL
        INSERT INTO worktags_budgets(budget_id, worktag_id, created_at, updated_at)
        SELECT 
          B.id AS budget_id,
          W.id AS worktag_id,
          NOW() AS created_at,
          NOW() AS updated_at
        FROM worktags_budgets_fdm_translator T 
        INNER JOIN budgets B 
          ON T.budgetno = B.budgetno
        INNER JOIN worktags W 
          ON T.tag_type = W.tag_type
          AND T.workday_code = W.workday_code
        LEFT OUTER JOIN worktags_budgets existing
          ON B.id = existing.budget_id
          AND W.id = existing.worktag_id
        WHERE existing.id IS NULL
        ORDER BY B.id, W.id
        _SQL;

        DB::statement($sql);
    }

    private function deleteStaleLinks()
    {
        $sql = <<<_SQL
        DELETE WB.*
        FROM worktags_budgets WB
        INNER JOIN budgets B 
          ON WB.budget_id = B.id
        INNER JOIN worktags W 
          ON WB.worktag_id = W.id
        LEFT OUTER JOIN worktags_budgets_fdm_translator T 
          ON T.tag_type = W.tag_type
        AND T.workday_code = W.workday_code
        AND T.budgetno = B.budgetno
        WHERE T.id IS NULL
        AND WB.edited_by IS NULL
        _SQL;

        DB::statement($sql);
    }
}
