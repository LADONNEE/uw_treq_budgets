<?php

namespace App\Forms\Budget;

use App\Forms\Form;
use App\Forms\Validation\PersonExists;
use App\Models\Budget;
use App\Models\BudgetLog;
use App\Utilities\FirstWords;
use Illuminate\Support\Facades\DB;


class BudgetForm extends Form
{
    protected $budget;
    protected $table_uw_persons;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
        $this->table_uw_persons = config('app.database_shared') . '.uw_persons'; 
    }

    public function createInputs()
    {
        $this->add('business_person_id', 'hidden');
        $this->add('person_search');
        $this->add('fiscal_person_id', 'select')
            ->options(ManagerForm::optionsManagers());
        $this->add('reconciler_person_id', 'select')
            ->options([$this, 'optionsReconciler']);
        $this->add('purpose_brief', 'text')
            ->help('Displayed on the home page. Max 50 characters.')
            ->set('maxlength', 50);
        $this->add('purpose', 'textarea')
            ->help('This long description is shown on a budget\'s detail page');
        $this->add('people_data', 'hidden');
        $this->add('food', 'select')
            ->options(Budget::$foodOptions)
            ->firstOption('(missing)');
        $this->add('food_note', 'textarea')
            ->help('Additional food instructions per contract, policy, etc.');
        $this->add('visible', 'boolean')
            ->help('Choose if Budget should show up in TREQ suggestions');
    }

    public function initValues()
    {
        $this->fill($this->budget);
        $this->input('purpose')->initialValue($this->budget->purpose->note);
        $this->input('food_note')->initialValue($this->budget->foodNote->note);
        if ($this->budget->business_person_id) {
            $this->set('person_search', eFirstLast($this->budget->business));
        }
    }

    public function validate()
    {
        $this->check('fiscal_person_id')->inList();
        $this->check('reconciler_person_id')->inList();
        if (!$this->input('business_person_id')->isEmpty()) {
            (new PersonExists)->isValid('business_person_id', []);
        }
    }

    public function commit()
    {
        $managerChanged = $this->budget->fiscal_person_id != $this->value('fiscal_person_id');

        $this->budget->fiscal_person_id = $this->get('fiscal_person_id');
        $this->budget->reconciler_person_id = $this->get('reconciler_person_id');
        $this->budget->business_person_id = $this->get('business_person_id');
        $this->budget->purpose_brief = $this->makePurposeBrief($this->get('purpose_brief'), $this->get('purpose'));
        $this->budget->food = $this->get('food');
        $this->budget->visible = $this->get('visible');
        $this->budget->save();

        if ($managerChanged) {
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $this->budget->manager, $this->budget);
        }

        $this->budget->purpose->edit($this->get('purpose'), user());
        $this->budget->foodNote->edit($this->get('food_note'), user());
    }

    public function optionsReconciler()
    {
        $out = ['' => '(no reconciler)'];
        $fiscals = DB::table($this->table_uw_persons . ' AS p')
            ->join('reconcilers_view', 'reconcilers_view.person_id', '=', 'p.person_id')
            ->select('reconcilers_view.person_id', 'p.firstname', 'p.lastname')
            ->orderBy('p.firstname')
            ->orderBy('p.lastname')
            ->get();
        foreach ($fiscals as $fiscal) {
            $out[$fiscal->person_id] = "{$fiscal->firstname} {$fiscal->lastname}";
        }
        return $out;
    }

    private function makePurposeBrief($brief, $full)
    {
        if ($brief) {
            return substr($brief, 0, 50);
        }
        if (!$full) {
            return null;
        }
        $fw = new FirstWords();
        return $fw->getFirstWords($full, 50);
    }
}
