<?php

namespace App\Updaters\Worktags;

use App\Edw\BudgetDataSource;
use App\Models\Worktag;
use App\Updaters\EdwParser;

class ImportWorktagsJob
{
    /**
     * Workday worktag types with load strategies
     */
    private $tagTypes = [
        Worktag::TYPE_COST_CENTER,
        Worktag::TYPE_PROGRAM,
    ];

    protected $edw;
    protected $parser;

    public function __construct(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->edw = $edw;
        $this->parser = $parser;
    }

    public function run()
    {
        // Only loading Hierarchy for Cost Centers
        (new HierarchyTask($this->edw, $this->parser))->run([Worktag::TYPE_COST_CENTER]);
        (new LinkHierarchyTask())->run();
        (new WorktagsTask($this->edw, $this->parser))->run($this->tagTypes);
        (new WorktagsByCostCenterTask($this->edw, $this->parser))->run();
        (new LinkGrantsTask())->run();
        // Load Principal Investigators as fiscal_person_id for worktags of type Grant
        (new AssigneesTask($this->edw, $this->parser))->run([Worktag::TYPE_GRANT]);

    }    
}
