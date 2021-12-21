<?php
namespace App\Workflows;

use App\Models\Approval;
use App\Models\EffortReport;

class DefaultBudgetApprovalStep extends ApprovalsStep
{
    public $stage = EffortReport::STAGE_DEFAULT_BUDGET;

    public function getApprovals()
    {
        return Approval::where('report_id', $this->effortReport->id)
            ->where('type', Approval::TYPE_DEFAULT_BUDGET)
            ->get();
    }
}
