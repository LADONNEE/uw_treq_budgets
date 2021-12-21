<?php

namespace App\Workflows;

use App\Models\Approval;
use App\Models\EffortReport;

class AddedApprovalsStep extends ApprovalsStep
{
    public $stage = EffortReport::STAGE_APPROVAL;

    public function getApprovals()
    {
        return Approval::where('report_id', $this->effortReport->id)
            ->where('type', Approval::TYPE_APPROVAL)
            ->get();
    }
}
