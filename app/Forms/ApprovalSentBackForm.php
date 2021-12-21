<?php

namespace App\Forms;

use App\Events\EffortReportUpdated;
use App\Models\Approval;
use App\Models\Contact;

class ApprovalSentBackForm extends Form
{
    public $approval;

    public function __construct(Approval $approval)
    {
        $this->approval = $approval;
    }

    public function createInputs()
    {
        $this->add('message');
    }

    public function validate()
    {
        // no validation needed
    }

    public function commit()
    {
        $this->approval->fill([
            'response' => Approval::RESPONSE_SENTBACK,
            'message' => $this->value('message'),
            'completed_by_contact_id' => Contact::personToContact(user()->person_id),
            'responded_at' => now(),
        ]);
        $this->approval->save();

        event(new EffortReportUpdated($this->approval->effortReport));
    }
}
