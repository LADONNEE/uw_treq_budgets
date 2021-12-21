<?php

namespace App\Repositories\Budget;

use App\Models\BudgetBiennium;
use App\Services\BienniumsService;

class BudgetSearch
{
    protected $biennium;
    protected $report;
    protected $searchterm;
    public $sql;

    public function __construct($searchterm, $biennium = null)
    {
        $this->setSearchTerm($searchterm);
        $this->biennium = ($biennium) ?: (new BienniumsService())->current();
    }

    public function getSearchTerm()
    {
        return $this->searchterm;
    }

    public function budgets()
    {
        if (!is_array($this->report)) {
            $this->load();
        }
        return $this->report;
    }

    public function load()
    {
        if (!$this->searchterm) {
            $this->report = [];
            return;
        }
        $terms = $this->parseTerms($this->searchterm);
        $query = BudgetBiennium::from('budget_biennium_view AS b')
            ->select('b.*')
            ->leftJoin('shared.uw_persons AS pi', function($join) {
                $join->on('b.pi_person_id', '=', 'pi.person_id');
            })
            ->leftJoin('shared.uw_persons AS fiscal', function($join) {
                $join->on('b.fiscal_person_id', '=', 'fiscal.person_id');
            })
            ->leftJoin('shared.uw_persons AS bus', function($join) {
                $join->on('b.business_person_id', '=', 'bus.person_id');
            })
            ->where('b.biennium', $this->biennium)
            ->orderBy('b.budgetno');
        foreach ($terms['budgets'] as $term) {
            $query->where('b.BudgetNbr', $term);
        }
        foreach ($terms['budget4'] as $term) {
            $query->where(function($query) use($term) {
                $query->where('b.budgetno', 'LIKE', '%'.$term);
            });
        }
        foreach ($terms['orgcodes'] as $term) {
            $query->where(function($query) use($term) {
                $query->where('b.OrgCode', 'LIKE', $term.'%');
            });
        }
        if ($terms['status']) {
            $statuses = $terms['status'];
            $query->where(function($query) use($statuses) {
                $query->whereIn('b.BudgetStatus', $statuses);
            });
        }
        foreach ($terms['names'] as $term) {
            $query->where(function($query) use($term) {
                $query->Where('fiscal.uwnetid', 'LIKE', $term.'%')
                    ->orWhere('fiscal.firstname', 'LIKE', $term.'%')
                    ->orWhere('fiscal.lastname', 'LIKE', $term.'%')
                    ->orWhere('bus.uwnetid', 'LIKE', $term.'%')
                    ->orWhere('bus.firstname', 'LIKE', $term.'%')
                    ->orWhere('bus.lastname', 'LIKE', $term.'%')
                    ->orWhere('pi.uwnetid', 'LIKE', $term.'%')
                    ->orWhere('pi.firstname', 'LIKE', $term.'%')
                    ->orWhere('pi.lastname', 'LIKE', $term.'%')
                    ->orWhere('b.PrincipalInvestigator', 'LIKE', '%'.$term.'%')
                    ->orWhere('b.name', 'LIKE', '%'.$term.'%');
            });
        }
        $this->report = $query->get();
        $this->sql = $query->toSql();
    }

    public function parseTerms($query)
    {
        $words = explode(' ', $query);
        $terms = [
            'names'    => [],
            'budgets'  => [],
            'budget4'  => [],
            'orgcodes' => [],
            'status'   => [],
        ];
        foreach ($words as $word) {
            if (preg_match('/^\d\d\d\d\d\d$/', $word)) {
                $terms['budgets'][] = $word;
            } elseif (preg_match('/^\d\d\-\d\d\d\d$/', $word)) {
                $terms['budgets'][] = str_replace('-', '', $word);
            } elseif (preg_match('/^\d\d\d\d$/', $word)) {
                $terms['budget4'][] = $word;
            }  elseif (preg_match('/^\d\d\d+$/', $word)) {
                $terms['orgcodes'][] = $word;
            } elseif (preg_match('/^status=\d\d?\d?\d?$/', $word)) {
                $statuses = substr($word, 7);
                $terms['status'] = str_split($statuses);
            } else {
                $terms['names'][] = $word;
            }
        }
        return $terms;
    }

    public function setSearchTerm($searchterm)
    {
        $this->searchterm = preg_replace('/\s+/', ' ', strtolower(trim($searchterm)));
    }

}
