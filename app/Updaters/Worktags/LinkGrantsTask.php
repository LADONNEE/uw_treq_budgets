<?php

namespace App\Updaters\Worktags;

use App\Models\Budget;
use App\Models\Worktag;
use App\Models\WorktagBudget;

/**
 * Look for budgetno strings inside Worktag names and link Worktags to Budgets
 */
class LinkGrantsTask
{
    public function run()
    {
        foreach ($this->todo() as $worktag_id) {
            $worktag = Worktag::find($worktag_id);
            if (!$worktag instanceof Worktag) {
                continue;
            }
            $this->linkGrantWorktagToBudgetno($worktag);
        }
    }

    private function todo()
    {
        return Worktag::query()
            ->where('tag_type', 'Grant')
            ->pluck('id');
    }

    private function linkGrantWorktagToBudgetno(Worktag $worktag)
    {
        $budgetno = $this->findBudgetnoInWorktagName($worktag->name);

        if (!$budgetno) {
            return;
        }

        $budget_id = $this->budgetnoToId($budgetno);

        if (!$budget_id) {
            return;
        }

        WorktagBudget::query()->firstOrCreate([
            'budget_id' => $budget_id,
            'worktag_id' => $worktag->id,
        ]);
    }

    /**
     * Initial Grant Worktag names have Budget Number and Biennium embedded in name
     * Example: GR019332 TRAINING CONSORTIUM - 67-3458 - 2021
     */
    private function findBudgetnoInWorktagName($name)
    {
        $matches = [];
        return (preg_match('/- (\d{2}-\d{4}) - 20\d\d/', $name, $matches)) ? $matches[1] : null;
    }

    private function budgetnoToId($budgetno)
    {
        return Budget::query()
            ->where('budgetno', $budgetno)
            ->value('id');
    }
}
