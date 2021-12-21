<?php
namespace App\Workflows;

use App\Models\Approval;
use App\Models\EffortReport;

class FacultyApprovalsStep extends ApprovalsStep
{
    public $stage = EffortReport::STAGE_FACULTY;

    public function getApprovals()
    {
        return Approval::where('report_id', $this->effortReport->id)
            ->where('type', Approval::TYPE_FACULTY)
            ->get();
    }
}
