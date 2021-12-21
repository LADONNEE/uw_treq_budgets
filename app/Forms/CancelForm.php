<?php
namespace App\Forms;

use App\Models\EffortReport;
use App\Models\EffortReportNote;

class CancelForm extends Form
{
    protected $effortReport;

    public function __construct(EffortReport $effortReport)
    {
        $this->effortReport = $effortReport;
    }

    public function createInputs()
    {
        $this->add('note', 'textarea')
            ->class('phrase')->required();
    }

    public function initValues()
    {
        //
    }

    public function validate()
    {
        //
    }

    public function commit()
    {
        $this->effortReport->stage = EffortReport::STAGE_CANCELED;
        $this->effortReport->save();

        if (!$this->input('note')->isEmpty()) {
            $this->commitNote();
        }
    }

    public function commitNote()
    {
        $note = new EffortReportNote([
            'report_id' => $this->effortReport->id,
            'created_by' => user()->person_id
        ]);

        $note->note = $this->input('note')->getFormValue();
        $note->section = 'canceled';
        $note->save();
    }

}
