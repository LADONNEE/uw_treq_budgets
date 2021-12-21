<?php
namespace App\Forms;

use App\Events\EffortReportUpdated;
use App\Models\Approval;
use App\Models\Contact;
use Carbon\Carbon;

class ApprovalApprovedForm extends Form
{
    public $approval;
    private $responseValue;

    public function __construct(Approval $approval, $responseValue)
    {
        $this->approval = $approval;
        $this->responseValue = $responseValue;
    }

    public function createInputs()
    {
        $this->add('message');
        $this->add('revisit_at', 'Revisit');
    }

    public function validate()
    {
        if ($this->input('revisit_at')->getFormValue()) {
            $ts = strtotime($this->input('revisit_at')->getFormValue());
            if ($ts === false) {
                $this->input('revisit_at')->error('Not a valid date, use M/D/YYYY');
            }
            $this->input('revisit_at')->setFormValue(Carbon::createFromTimestamp($ts));
        }
    }

    public function commit()
    {
        if (!$this->approval->responded_at) {
            $this->approval->fill([
                'response' => $this->responseValue,
                'message' => $this->value('message'),
                'completed_by_contact_id' => Contact::personToContact(user()->person_id),
                'responded_at' => now(),
            ]);
            $this->approval->save();
        }

        $effortReport = $this->approval->effortReport;
        if ($this->input('revisit_at')->getFormValue() || $effortReport->revisit_at) {
            $effortReport->revisit_at = $this->value('revisit_at');
            $effortReport->revisit_notified_at = $this->value('revisit_at') ? null : $effortReport->revisit_notified_at;
            $effortReport->save();
        }

        event(new EffortReportUpdated($this->approval->effortReport));
    }
}
