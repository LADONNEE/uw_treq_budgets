<?php

namespace App\Http\Controllers;

use App\Api\ApprovalApiItem;
use App\Forms\ApprovalApprovedForm;
use App\Forms\ApprovalCreateForm;
use App\Forms\ApprovalSentBackForm;
use App\Models\Approval;
use App\Models\EffortReport;

class UpdateApproval extends Controller
{
    public function __invoke(EffortReport $effortReport)
    {
        $data = request()->all();

        if ($data['action'] === 'express-approve') {
            $this->expressApprove($effortReport, $data);
        } else {
            $form = $this->getForm($effortReport, $data['action'] ?? 'missing', $data['approval_id'] ?? null);

            if ($form->process($data)) {
                $api = new ApprovalApiItem($form->approval, user());
                return response()->json($api);
            }

            return response()->json([
                'result' => 'error',
                'messages' => $form->getErrors()
            ], 400);
        }
    }

    private function getForm(EffortReport $effortReport, $type, $approval_id)
    {
        switch ($type) {
            case 'approval':
                return new ApprovalCreateForm($effortReport);
            case 'approve':
                $task = $this->getApproval($approval_id);
                if (!$task->canComplete(user()) && !$task->canCompleteWorkday(user())) {
                    abort(403, 'Not authorized to respond to this approval');
                }
                return new ApprovalApprovedForm($task, Approval::RESPONSE_APPROVED);
            case 'edit':
                $task = $this->getApproval($approval_id);
                return new ApprovalApprovedForm($task, Approval::RESPONSE_APPROVED);
            case 'revision':
                $task = $this->getApproval($approval_id);
                return new ApprovalApprovedForm($task, Approval::RESPONSE_REVISION);
            case 'send-back':
                $task = $this->getApproval($approval_id);
                if (!$task->canComplete(user()) && !$task->canCompleteWorkday(user())) {
                    abort(403, 'Not authorized to respond to this approval');
                }
                return new ApprovalSentBackForm($task);
            default:
                abort(404, "Unexpected action '{$type}'");
        }
    }

    private function expressApprove(EffortReport $effortReport, $data)
    {
        if (hasRole('budget:admin')) {
            $approvals = $effortReport->approvals->whereNull('responded_at')->where('type', '<>', Approval::TYPE_COEPAY);
            $errors = [];

            foreach ($approvals as $approval) {
                $form = $this->getForm($effortReport, 'approve', $approval->id);

                if (!$form->process($data)) {
                    $errors[] = $form->getErrors();
                }
            }

            if ($errors) {
                return response()->json([
                    'result' => 'error',
                    'messages' => $errors
                ], 400);
            }
        } else {
            abort(403, 'Not authorized');
        }
    }

    /**
     * @param int $approval_id
     * @return Approval
     */
    private function getApproval($approval_id)
    {
        return Approval::findOrFail($approval_id);
    }
}
