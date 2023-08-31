<?php

namespace App\Reports;

use App\Models\Worktag;
use App\Models\WorktagHierarchy;

/**
 * Component used to represent Worktags and WorktagHierarchy in a tree structure
 *
 * One WorktagBranch represents a single WorktagHierarchy and has properties for
 * child hierarchy and Worktags.
 */
class WorktagBranch
{
    public $hierarchy_id;
    public $name;
    public $parent_id;
    public $tag_type;
    public $workday_code;

    /**
     * @var WorktagBranch[]
     */
    public $children = [];

    /**
     * @var Worktag[]
     */
    public $worktags = [];

    public function __construct(?WorktagHierarchy $hierarchy = null)
    {
        if ($hierarchy) {
            $this->name = $hierarchy->name;
            $this->hierarchy_id = $hierarchy->id;
            $this->parent_id = $hierarchy->parent_id;
            $this->tag_type = $hierarchy->tag_type;
            $this->workday_code = $hierarchy->workday_code;
        }
    }

    public function addChild(WorktagBranch $branch)
    {
        $this->children[] = $branch;
    }

    public function addWorktag(Worktag $worktag)
    {
        $this->worktags[] = $worktag;
    }
}