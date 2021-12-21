<?php
/**
 * @package edu.uw.environment.college
 */
/**
 * Repository for EDW Budget records
 */
namespace App\Repositories\Budget;

use App\Models\BudgetLog;
use App\Models\EdwBudget;
use App\Models\Person;
use App\Events\FiscalAssignedToBudget;

class EdwBudgetRepo
{

    /**
     * Assign fiscal manager to a list of budget numbers
     * @param integer $personId
     * @param array $budgetNbrs
     * @return array
     */
    public function assignFiscal($personId, $budgetNbrs)
    {
        $person = Person::find($personId);
        if (!$person) {
            return ['assigned' => 0];
        }
        $result = [
            'assigned'  => 0,
            'budgets'   => [],
            'person_id' => $person->person_id,
            'uwnetid'   => $person->uwnetid,
            'firstlast' => eFirstLast($person),
            'lastfirst' => eLastFirst($person),
        ];
        $budgets = EdwBudget::whereIn('BudgetNbr', $budgetNbrs)->get();
        foreach ($budgets as $budget) {
            $budget->fiscal_person_id = $person->person_id;
            $budget->save();
            event(new FiscalAssignedToBudget($budget, $person));
            $result['budgets'][] = $budget->BudgetNbr;
            ++ $result['assigned'];
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $person, $budget);
        }
        return $result;
    }

    /**
     * Remove the $personId from fiscal contact of all budgets
     * @param $personId
     */
    public function clearFiscal($personId)
    {
        EdwBudget::where('fiscal_person_id', $personId)->update([ 'fiscal_person_id' => null ]);
    }

}
