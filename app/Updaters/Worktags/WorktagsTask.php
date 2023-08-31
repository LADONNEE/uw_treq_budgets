<?php

namespace App\Updaters\Worktags;

use App\Edw\BudgetDataSource;
use App\Models\Worktag;
use App\Models\WorktagHierarchy;
use App\Updaters\EdwParser;

class WorktagsTask
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
        $results = $this->edw->getWorktags($tagType);

        $hierarchy = $this->loadHierarchyMap();

        foreach ($results as $row) {
            $data = $this->parse($row);

            $worktag = Worktag::firstOrNew([
                'tag_type' => $tagType,
                'workday_code' => $data['workday_code'],
            ]);
            $worktag->name = $data['name'];
            $worktag->hierarchy_id = $hierarchy[$data['HierarchyCode']] ?? null;
            $worktag->save();
        }
    }

    private function loadHierarchyMap(): array
    {
        return WorktagHierarchy::query()
            ->orderBy('workday_code')
            ->pluck('id', 'workday_code')
            ->all();
    }

    private function parse(array $row): array
    {
        return [
            'workday_code' => $this->parser->string($row['WorkdayCode']),
            'name' => $this->parser->string($row['WorktagName']),
            'HierarchyCode' => $this->parser->string($row['HierarchyCode']),
        ];
    }
}
