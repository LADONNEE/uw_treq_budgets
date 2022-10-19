<?php
namespace App\Reports;

use App\Auth\User;
use Illuminate\Support\Facades\DB;
use Config;

class UsersReport
{

    protected $table_shared;

    public function __construct()
    {
        $this->table_shared = Config::get('app.database_shared') ; 
    }

    public function getReport()
    {
        $results = DB::table('roles AS r')
            ->join($this->table_shared . '.uw_persons AS p', 'r.uwnetid', '=', 'p.uwnetid')
            ->select([
                'r.uwnetid',
                'r.role',
                'p.person_id',
                'p.firstname',
                'p.lastname',
            ])
            ->orderBy('p.lastname')
            ->orderBy('p.firstname')
            ->get();

        // transform results to single User -> array of roles
        $users = [];
        foreach ($results as $row) {
            if (isset($users[$row->uwnetid])) {
                $users[$row->uwnetid]->addRole($row->role);
            } else {
                $users[$row->uwnetid] = new User($row->uwnetid, $row);
                $users[$row->uwnetid]->addRole($row->role);
            }
        }

        return $users;
    }
}
