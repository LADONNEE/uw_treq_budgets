<?php

namespace App\Forms\Budget;

use App\Forms\Form;
use App\Models\Budget;
use App\Models\BudgetLog;
use Illuminate\Support\Facades\DB;
use Config;

class ManagerForm extends Form
{
    protected $budget;
    protected $table_uw_persons;

    public function __construct(Budget $contact)
    {
        $this->budget = $contact;
        $this->table_uw_persons = Config::get('app.database_shared') . '.uw_persons'; 
    }

    public function createInputs()
    {
        $this->add('manager', 'select')
            ->options(self::optionsManagers());
    }

    public function initValues()
    {
        $this->input('manager')->setFormValue($this->budget->fiscal_person_id);
    }

    public function validate()
    {
        $this->check('manager')->inList();
    }

    public function commit()
    {
        $fiscalChanged = $this->budget->fiscal_person_id != $this->value('manager');
        $this->budget->fiscal_person_id = $this->value('manager');
        $this->budget->save();
        if ($fiscalChanged) {
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $this->budget->manager, $this->budget);
        }
    }

    public static function optionsManagers()
    {
        $out = ['' => '(no budget manager)'];
        $fiscals = DB::table($this->table_uw_persons . ' AS p')
            ->join('managers_view', 'managers_view.person_id', '=', 'p.person_id')
            ->select('managers_view.person_id', 'p.firstname', 'p.lastname')
            ->orderBy('p.firstname')
            ->orderBy('p.lastname')
            ->get();
        foreach ($fiscals as $fiscal) {
            $out[$fiscal->person_id] = "{$fiscal->firstname} {$fiscal->lastname}";
        }
        return $out;
    }

}
