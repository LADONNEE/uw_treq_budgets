<?php

namespace App\Forms;

use App\Models\Approval;
use App\Models\Contact;
use App\Models\EffortReport;

class ApprovalCreateForm extends Form
{
    protected $effortReport;
    public $approval;

    public function __construct(EffortReport $effortReport)
    {
        $this->effortReport = $effortReport;
    }

    public function createInputs()
    {
        $this->add('person_id');
        $this->add('description');
    }

    public function validate()
    {
//        $this->checkPersonExists();
    }

    public function commit()
    {
        $this->approval = new Approval([
            'report_id' => $this->effortReport->id,
            'type' => Approval::TYPE_APPROVAL,
            'assigned_to_contact_id' => Contact::personToContact($this->value('person_id')),
            'sequence' => 0,
            'response' => Approval::RESPONSE_PENDING,
        ]);
        $this->approval->save();

        $effortReport = EffortReport::where('id', $this->effortReport->id)->first();
        $effortReport->stage = EffortReport::STAGE_APPROVAL;
        $effortReport->save();

    }
}
