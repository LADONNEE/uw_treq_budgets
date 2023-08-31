<?php

namespace App\Forms\CostCenter;

use App\Forms\Form;
use App\Models\Budget;
use App\Models\BudgetLog;
use Illuminate\Support\Facades\DB;

class ManagerForm extends Form
{
    protected $costcenter;

    public function __construct(Worktag $costcenter)
    {
        $this->costcenter = $costcenter;
    }

    public function createInputs()
    {
        $this->add('manager', 'select')
            ->options(self::optionsManagers());
    }

    public function initValues()
    {
        $this->input('manager')->setFormValue($this->costcenter->fiscal_person_id);
    }

    public function validate()
    {
        $this->check('manager')->inList();
    }

    public function commit()
    {
        $fiscalChanged = $this->costcenter->fiscal_person_id != $this->value('manager');
        $this->costcenter->fiscal_person_id = $this->value('manager');
        $this->costcenter->save();
        if ($fiscalChanged) {
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $this->costcenter->manager, $this->costcenter);
        }
    }

    public static function optionsManagers()
    {
        $out = ['' => '(no cost center approver)'];
        $fiscals = DB::table('shared.uw_persons AS p')
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
