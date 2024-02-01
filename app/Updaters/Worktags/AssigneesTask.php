<?php

namespace App\Updaters\Worktags;

use App\Edw\BudgetDataSource;
use App\Models\Worktag;
use App\Updaters\EdwParser;

class AssigneesTask
{
    protected $edw;
    protected $parser;

    public function __construct(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->edw = $edw;
        $this->parser = $parser;
    }

    public function run(array $tagTypes)
    {
        foreach ($tagTypes as $tagType) {
            $this->loadTagType($tagType);
        }
    }

    private function loadTagType($tagType)
    {
        $results = $this->edw->getWorktagAssignee($tagType);

        foreach ($results as $row) {
            $data = $this->parse($row);

            $worktag = Worktag::where('workday_code', $data['workday_code'])->first();
            
            if ($worktag) {
                $worktag->fiscal_person_id = $data['assignee'];
                $worktag->save();
            }
            

        }
    }

    private function parse(array $row): array
    {
        return [
            'workday_code' => $this->parser->string($row['DriverWorktagID']),
            'assignee' => $this->parser->string($row['DriverWorktagPersonKey']),
        ];
    }
}
