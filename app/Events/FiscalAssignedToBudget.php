<?php
/**
 * @namespace edu.uw.uaa.college
 */

/**
 * Event fired when fiscal contact assigned to budget
 * @author hanisko
 */

namespace App\Events;

use App\Models\EdwBudget;
use App\Models\Person;
use Illuminate\Queue\SerializesModels;

class FiscalAssignedToBudget extends Event
{

    use SerializesModels;

    /**
     * @var EdwBudget
     */
    public $budget;
    /**
     * @var Person
     */
    public $person;

    public function __construct(EdwBudget $budget, Person $person)
    {
        $this->budget = $budget;
        $this->person = $person;
    }

}
