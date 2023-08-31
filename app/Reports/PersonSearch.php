<?php

namespace App\Reports;

use App\Models\Contact;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class PersonSearch
{
    protected $filtersOn = [
        'uwnetid',
        'lastname',
        'firstname',
        'employeeid'
    ];
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function getReport()
    {
        $query = Person::from('shared.uw_persons AS p')
            ->orderBy('p.lastname')
            ->orderBy('p.firstname')
            ->take(5);
        $this->addFilters('p', $query);

        $query->leftJoin('contacts AS c1', 'p.person_id', '=', 'c1.person_id')
            ->leftJoin('contacts AS c2', 'c1.fiscal_person_id', '=', 'c2.id')
            ->leftJoin('budgets AS b', 'c1.default_budget_id', '=', 'b.id')
            ->select('p.*', 'c1.default_budget_id', 'c1.fiscal_person_id', 'c1.end_at', 'b.budgetno', DB::raw('CONCAT(c2.firstname, " ", c2.lastname) AS fiscal_name'));

        return $query->get();
    }

    public function addFilters($table, $query)
    {
        foreach ($this->filtersOn as $field) {
            if (!empty($this->filters[$field])) {
                $query->where("$table.$field", 'like', $this->filters[$field] . '%');
            }
        }
    }

}
