<?php

namespace App\Updaters\Worktags;

use App\Edw\BudgetDataSource;
use App\Models\WorktagHierarchy;
use App\Updaters\EdwParser;

class HierarchyTask
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
        $results = $this->edw->getWorktagHierarchy($tagType);

        foreach ($results as $row) {
            $data = $this->parse($row);

            $hierarchy = WorktagHierarchy::firstOrNew([
                'tag_type' => $tagType,
                'workday_code' => $data['workday_code'],
            ]);
            $hierarchy->fill($data);
            $hierarchy->save();
        }
    }

    private function parse(array $row): array
    {
        return [
            'workday_code' => $this->parser->string($row['WorkdayCode']),
            'name' => $this->parser->string($row['HierarchyName']),
            'parent_workday_code' => $this->parser->string($row['ParentWorkdayCode']),
        ];
    }
}
