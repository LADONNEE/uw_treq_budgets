<?php

namespace App\Reports;

use App\Models\Worktag;

class WorktagsWithBudgetCount
{
    public static function query()
    {
        $countSubquery = 'SELECT worktag_id, COUNT(1) AS budget_count FROM worktags_budgets GROUP BY worktag_id';
        return Worktag::query()
            ->leftJoinSub($countSubquery, 'budget_refs', 'worktags.id', 'budget_refs.worktag_id')
            ->selectRaw('worktags.*, budget_refs.budget_count')
            ->orderBy('worktags.tag_type')
            ->orderBy('worktags.workday_code');
    }
}