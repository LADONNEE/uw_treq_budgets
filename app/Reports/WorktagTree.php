<?php

namespace App\Reports;

use App\Models\Worktag;
use App\Models\WorktagHierarchy;

/**
 * Top level component used to represent WorktagHierarchy and Worktags in a tree structure
 *
 * A WorktagTree represents a collection of Worktags and WorktagHierarchies within a common
 * tag type.
 */
class WorktagTree extends WorktagBranch
{
    public function __construct($hierarchy, $tag_type)
    {
        parent::__construct();
        $this->name = $hierarchy;
        $this->tag_type = $tag_type;
        $this->load();
    }

    public function load()
    {
        $map = $this->indexedBranches();

        foreach ($map as $h) {
            if ($h->parent_id && isset($map[$h->parent_id])) {
                $map[$h->parent_id]->addChild($h);
            } else {
                $this->addChild($h);
            }
        }

        $worktags = $this->getWorktags();

        foreach ($worktags as $w) {
            if ($w->hierarchy_id && isset($map[$w->hierarchy_id])) {
                $map[$w->hierarchy_id]->addWorktag($w);
            } else {
                $this->addWorktag($w);
            }
        }
    }

    /**
     * @return WorktagBranch[]
     */
    private function indexedBranches()
    {
        $items = WorktagHierarchy::query()
            ->where('tag_type', $this->tag_type)
            ->orderBy('workday_code')
            ->get();

        $out = [];
        foreach ($items as $hierarchy) {
            $out[$hierarchy->id] = new WorktagBranch($hierarchy);
        }

        return $out;
    }
    /**
     * @return Worktag[]
     */
    private function getWorktags()
    {
        $countSubquery = 'SELECT worktag_id, COUNT(1) AS budget_count FROM worktags_budgets GROUP BY worktag_id';
        return Worktag::query()
            ->leftJoinSub($countSubquery, 'budget_refs', 'worktags.id', 'budget_refs.worktag_id')
            ->selectRaw('worktags.*, budget_refs.budget_count')
            ->where('worktags.tag_type', $this->tag_type)
            ->orderBy('worktags.workday_code')
            ->get();
    }
}