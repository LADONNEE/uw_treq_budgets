<?php

namespace App\AuthNotify;

use Illuminate\Support\Facades\DB;

/**
 * Decorator that provides multiple users roles as a single standardized string.
 *
 * The logic of choosing which roles should be reported to COLLEGE Person auth log
 * is generally embedded in a view in the `shared` database. This allows COLLEGE
 * Person to handle one off notifications and to run nightly bulk user imports
 * and get the same role values from each.
 */
class CollegeAuthNotifyMultipleRoles extends CollegeAuthNotify
{
    /**
     * Table that provides a row per UW NetID with single string summary of multiple rows.
     *
     * Typically this table is defined in the shared-db project (`shared` database) and is
     * a SQL view that concatenates multiple rows.
     *
     * @var string
     */
    //const TABLE_ROLE_SUMMARY = 'shared.users_budgets';
    private $table_role_summary;

    public function __construct() {
            $this->table_role_summary = config('app.database_shared') . '.users_budgets'; 
    } 


    public function notify($uwnetid, $role, $actor_uwnetid, $person_data = [])
    {
        $roleSummary = DB::table(self::constant($this->table_role_summary))
            ->where('uwnetid', $uwnetid)
            ->value('role');

        parent::notify($uwnetid, $roleSummary, $actor_uwnetid, $person_data);
    }
}
