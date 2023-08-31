<?php

namespace App\Forms\Budget;

use App\Forms\Form;
use App\Forms\Validation\PersonExists;
use App\Models\ProjectCode;
use App\Utilities\FirstWords;
use Illuminate\Support\Facades\DB;


class ProjectCodeForm extends Form
{
    protected $projectcode;
    protected $table_uw_persons;

    public function __construct(ProjectCode $projectcode)
    {
        $this->projectcode = $projectcode;
        $this->table_uw_persons = config('app.database_shared') . '.uw_persons'; 
    }

    public function createInputs()
    {

        $this->add('code')
            ->help('Max 6 characters.')
            ->set('maxlength', 6);
        $this->add('description');
        $this->add('allocation_type_frequency');
        $this->add('purpose');
        $this->add('pre_approval_required');
        $this->add('action_item');
        $this->add('workday_code');
        $this->add('workday_description');
        $this->add('authorizer_person_id', 'select')
            ->options([$this, 'optionsAuthorizer']);
        $this->add('fiscal_person_id', 'select')
            ->options([$this, 'optionsFiscal']);

        
    }

    public function initValues()
    {
        $this->fill($this->projectcode);
        
        // if ($this->projectcode->authorizer_person_id) {
        //     $this->set('authorizer_person_id', eFirstLast($this->projectcode->authorizer_person_id));
        // }

        // if ($this->projectcode->fiscal_person_id) {
        //     $this->set('fiscal_person_id', eFirstLast($this->projectcode->fiscal_person_id));
        // }
    }

    public function validate()
    {
        /*$this->check('fiscal_person_id')->inList();
        $this->check('reconciler_person_id')->inList();
        if (!$this->input('business_person_id')->isEmpty()) {
            (new PersonExists)->isValid('business_person_id', []);
        }*/
    }

    public function commit()
    {
        //$managerChanged = $this->budget->fiscal_person_id != $this->value('fiscal_person_id');

        $this->projectcode->code = $this->get('code');
        $this->projectcode->description = $this->get('description');
        $this->projectcode->allocation_type_frequency = $this->get('allocation_type_frequency');
        $this->projectcode->purpose = $this->get('purpose');
        $this->projectcode->pre_approval_required = $this->get('pre_approval_required');
        $this->projectcode->action_item = $this->get('action_item');
        $this->projectcode->workday_code = $this->get('workday_code');
        $this->projectcode->workday_description = $this->get('workday_description');
        $this->projectcode->authorizer_person_id = $this->get('authorizer_person_id');
        $this->projectcode->fiscal_person_id = $this->get('fiscal_person_id');
        $this->projectcode->save();

        /*if ($managerChanged) {
            $log = new BudgetLog();
            $log->writeAssignFiscal(app('user')->uwnetid, $this->budget->manager, $this->budget);
        }*/

        //$this->budget->purpose->edit($this->get('purpose'), user());
        //$this->budget->foodNote->edit($this->get('food_note'), user());
    }

    public function optionsAuthorizer()
    {
        $out = ['' => '(no authorizer)'];
        $fiscals = DB::table($this->table_uw_persons . ' AS p')
            ->orderBy('p.firstname')
            ->orderBy('p.lastname')
            ->get();
        foreach ($fiscals as $fiscal) {
            $out[$fiscal->person_id] = "{$fiscal->firstname} {$fiscal->lastname}";
        }
        return $out;
    }

    public function optionsFiscal()
    {
        $out = ['' => '(no fiscal person)'];
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
