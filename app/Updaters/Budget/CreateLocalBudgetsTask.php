<?php

namespace App\Updaters\Budget;

use App\Models\Budget;
use App\Models\Contact;
use App\Models\UwBudget;
use App\Updaters\ContactUpdater;
use App\Updaters\EdwParser;

/**
 * Make sure there is a budgets record for every budgetno that appears in uw_budgets_cache
 * Link PI to local Person records
 */
class CreateLocalBudgetsTask
{
    private $verbose;
    private $contacts;
    private $parser;

    public function __construct($verbose = false, ?EdwParser $parser = null)
    {
        $this->verbose = $verbose;
        $this->parser = $parser ?? new EdwParser();
    }

    public function run()
    {
        $this->log(' .. CreateLocalBudgetsTask starting');
        $todo = $this->todo();

        if (count($todo) === 0) {
            $this->log(' .. No missing budgetnos for budgets, done.');
            return;
        }

        foreach ($todo as $uwBudget) {
            $this->create($uwBudget);
        }

        $this->log(' .. CreateLocalBudgetsTask done.');
    }

    /**
     * @return UwBudget[]
     */
    private function todo()
    {
        return UwBudget::from('uw_budgets_cache AS w')
            ->select('w.*')
            ->leftJoin('budgets AS b', 'w.budgetno', '=', 'b.budgetno')
            ->whereNull('b.id')
            ->get();
    }

    public function create(UwBudget $uw)
    {
        if (!$uw->budgetno) {
            $this->log(" x. Skipping empty budgetno ({$uw->BudgetNbr} {$uw->BudgetName})");
            return;
        }
        $budget = Budget::firstOrNew([
            'budgetno' => $uw->budgetno
        ]);
        if (!$budget->pi_person_id && $uw->PrincipalInvestigatorId) {
            $contact = $this->importContact($uw->PrincipalInvestigator ?? 'Firstname Lastname', $uw->PrincipalInvestigatorId);
            $budget->pi_person_id = $contact->person_id;
        }
        $budget->save();

        $this->log(" +. Added local Budget ({$budget->budgetno})");
    }

    /**
     * @param string $lastFirst
     * @param string $employeeid
     * @return Contact
     */
    public function importContact($lastFirst, $employeeid)
    {
        $name = $this->parser->lastfirst($lastFirst);
        $contactData = [
            'firstname' => $name['first'],
            'lastname' => $name['last'],
            'employeeid' => $employeeid,
        ];
        return ContactUpdater::updateOrCreateContact($contactData);
    }

    private function log($message)
    {
        if ($this->verbose) {
            echo $message, "\n";
        }
    }
}
