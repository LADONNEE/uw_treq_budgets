<?php

namespace App\Http\Controllers;

use App\Models\Worktag;
use App\Models\WorktagHierarchy;
use App\Reports\WorktagsWithBudgetCount;
use Illuminate\Support\Facades\DB;

class WorktagsController extends Controller
{
    public function index()
    {
        $this->authorize('uwft');

        $worktags = WorktagsWithBudgetCount::query()
            ->with('costCenter')
            ->get();

        return view('worktags.index', compact('worktags'));
    }

    public function show(Worktag $worktag)
    {
        $this->authorize('uwft');

        $hierarchyTree = $this->getHierarchy($worktag->hierarchy_id);

        return view('worktags.show', compact('worktag', 'hierarchyTree'));
    }

    /**
     * @TODO move this, it is here as an experiment to figure out how to implement it
     */
    private function getHierarchy($hierarchy_id)
    {
        if (!$hierarchy_id) {
            return [];
        }

        $sql = <<<_SQL
        with recursive wh as (
          select     worktag_hierarchy.*
          from       worktag_hierarchy
          where      id = ?
          union all
          select     p.*
          from       worktag_hierarchy p
          inner join wh
                  on p.id = wh.parent_id
        )
        select * from wh;
        _SQL;

        $results = DB::select($sql, [$hierarchy_id]);
        $out = [];

        foreach ($results as $item) {
            $h = new WorktagHierarchy();
            $h->forceFill((array) $item);
            $out[] = $h;
        }

        return collect($out)->reverse();
    }
}
