<?php

namespace App\Forms\Costcenter;

use App\Forms\Form;
use App\Forms\Validation\PersonExists;
use App\Models\Worktag;
use App\Models\BudgetLog;
use App\Utilities\FirstWords;
use Illuminate\Support\Facades\DB;

class CostcenterForm extends Form
{
    protected $costcenter;

    public function __construct(Worktag $costcenter)
    {
        $this->costcenter = $costcenter;
    }

    public function createInputs()
    {
        
        $this->add('fiscal_person_id', 'select')
            ->options(ManagerForm::optionsManagers());
        
    }

    public function initValues()
    {
        $this->fill($this->costcenter);
        
    }

    public function validate()
    {
        //$this->check('fiscal_person_id')->inList();
        
    }

    public function commit()
    {
        $managerChanged = $this->costcenter->fiscal_person_id != $this->value('fiscal_person_id');

        $this->costcenter->fiscal_person_id = $this->get('fiscal_person_id');
        $this->costcenter->save();

        if ($managerChanged) {
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $this->costcenter->fiscal_person_id, $this->costcenter);
        }

    }

    
}
