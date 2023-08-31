<?php

namespace App\Updaters\Worktags;

use Illuminate\Support\Facades\DB;

class LinkHierarchyTask
{
    public function run()
    {
        $sql = <<<_SQL
        UPDATE worktag_hierarchy H
        INNER JOIN worktag_hierarchy PH
        ON H.parent_workday_code = PH.workday_code
        SET H.parent_id = PH.id;
        _SQL;

        DB::statement($sql);
    }
}
