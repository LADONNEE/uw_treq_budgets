<?php
namespace App\Workflows;

use App\Models\Approval;
use App\Models\EffortReport;

class CoePayApprovalStep extends ApprovalsStep
{
    public $stage = EffortReport::STAGE_UWORGPAY;

    public function getApprovals()
    {
        return Approval::where('report_id', $this->effortReport->id)
            ->where('type', Approval::TYPE_UWORGPAY)
            ->get();
    }
}
