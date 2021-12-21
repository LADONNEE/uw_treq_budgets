<?php

namespace App\Workflows;

use App\Models\EffortReport;

class ApprovalsStep
{
    protected $effortReport;
    public $stage = EffortReport::STAGE_APPROVAL;

    public function __construct(EffortReport $effortReport)
    {
        $this->effortReport = $effortReport;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function isComplete($approvals): bool
    {
        foreach ($approvals as $approval) {
            if (!$approval->isApproved()) {
                return false;
            }
        }
        return true;
    }

    public function isSentBack($approvals): bool
    {
        foreach ($approvals as $approval) {
            if ($approval->isSentBack()) {
                return true;
            }
        }
        return false;
    }
}
