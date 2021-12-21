<?php

namespace App\Workflows;

use App\Models\EffortReport;

class EffortReportWorkflow
{
    public function update(EffortReport $effortReport): void
    {
        if ($effortReport->isComplete()) {
            return;
        }

        $steps = [
            new AddedApprovalsStep($effortReport),
            new BudgetApprovalsStep($effortReport),
            new DefaultBudgetApprovalStep($effortReport),
            new FacultyApprovalsStep($effortReport),
            new CoePayApprovalStep($effortReport),
        ];

        foreach ($steps as $step) {
            $approvals = $step->getApprovals();
            $stepStage = $step->getStage();

            if ($step->isComplete($approvals)) {
                continue;
            } else if ($step->isSentBack($approvals)) {
                $effortReport->sendBack();
                return;
            } else if ($effortReport->stage !== $stepStage) {
                $effortReport->stage = $stepStage;
                $effortReport->save();
            }
            return;
        }

        $effortReport->stage = EffortReport::STAGE_APPROVED;
        $effortReport->save();
    }
}
