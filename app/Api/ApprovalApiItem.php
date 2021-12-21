<?php

namespace App\Api;

use App\Auth\User;
use App\Models\Approval;

class ApprovalApiItem
{
    public $assigned_to;
    public $assignee;
    public $behalf;
    public $canApprove;
    public $canComplete;
    public $canCompleteWorkday;
    public $canDelete;
    public $canReassign;
    public $completedAt;
    public $completer;
    public $createdAt;
    public $creator;
    public $currentStage;
    public $description;
    public $hasWorkdayRole;
    public $icon;
    public $id;
    public $isApproved;
    public $isCoepay;
    public $isComplete;
    public $isReportComplete;
    public $isSentBack;
    public $isVoid;
    public $message;
    public $name;
    public $response;
    public $responseSummary;
    public $responseType;
    public $revisitDate;
    public $show;
    public $sequence;
    public $taskSummary;
    public $type;
    public $userIsAdmin;
    public $userIsCreator;

    public function __construct(Approval $approval, User $user = null)
    {
        $this->fill($approval, $user);
    }

    public function fill(Approval $approval, User $user = null)
    {
        $this->id = $approval->id;
        $this->creator = "{$approval->effortReport->creator->firstname} {$approval->effortReport->creator->lastname}";
        $this->createdAt = eDate($approval->effortReport->created_at);
        $this->isCoepay = $approval->isCoePay();
        $this->type = $approval->type;
        $this->sequence = $approval->sequence;
        $this->description = $approval->effortReport->description;
        $this->assigned_to = $approval->assigned_to_contact_id;
        $this->assignee = $this->isCoepay ? 'COE Pay' : "{$approval->assignee->firstname} {$approval->assignee->lastname}";
        $this->response = $approval->response;
        $this->message = $approval->message;
        $this->isComplete = $approval->isComplete();
        $this->isReportComplete = $approval->effortReport->isComplete();
        $this->isApproved = $approval->isApproved();
        $this->canComplete = $approval->canComplete($user);
        $this->canCompleteWorkday = $approval->canCompleteWorkday($user);
        $this->hasWorkdayRole = $approval->hasWorkdayRole($user);
        $this->canDelete = false;
        $this->isVoid = false;
        $this->show = $approval->isComplete() || $approval->type === $approval->effortReport->stage;
        $this->canApprove = $approval->isReadyToApprove();
        $this->revisitDate = eDate($approval->effortReport->revisit_at);
        $this->userIsAdmin = hasRole('budget:admin', $user);
        $this->userIsCreator = $user->person_id === $approval->effortReport->creator->person_id;;
        $this->currentStage = $approval->effortReport->stage;

        if ($this->isComplete) {
            $this->completedAt = eDate($approval->responded_at);
            $this->responseType = $this->responseType($approval->response);
        }

        if ($approval->completed_by_contact_id !== $approval->assigned_to_contact_id && $this->response === Approval::RESPONSE_APPROVED) {
            $this->behalf = ", on behalf of {$approval->assignee->firstname} {$approval->assignee->lastname}";
        }

        $this->taskSummary = $this->summarizeRequest($approval);
        $this->responseSummary = $this->summarizeResponse($approval);
    }

    public function iconClasses()
    {
        $classes = ['fas'];
        switch ($this->response) {
            case Approval::RESPONSE_APPROVED:
            case Approval::RESPONSE_REVISION:
            $classes[] = 'fa-thumbs-up text-success';
                break;
            case Approval::RESPONSE_SENTBACK:
                $classes[] = 'fa-undo text-danger';
                break;
            case Approval::RESPONSE_COMPLETED:
                $classes[] = 'fa-check text-success';
                break;
            default:
                $classes[] = 'fa-question-circle';
                break;
        }
        return implode(' ', $classes);
    }

    public function responseType($response)
    {
        switch ($response) {
            case Approval::RESPONSE_APPROVED:
            return 'yes';
            case Approval::RESPONSE_SENTBACK:
                return 'no';
            case Approval::RESPONSE_COMPLETED:
                return 'complete';
            case Approval::RESPONSE_REVISION:
                return 'revision';
            default:
                return 'pending';
        }
    }

    public function summarizeResponse(Approval $approval)
    {
        if ($this->isComplete) {
            return "{$this->response} by {$this->completer}";
        }
        return "Needs response from {$this->assignee}";
    }

    public function summarizeRequest(Approval $approval)
    {
        if ($this->response === Approval::RESPONSE_REVISION) {
            return "{$this->creator} marked this as approval not needed from {$this->assignee}";
        }

        if ($this->isApproved && $this->isCoepay) {
            return "Entered in Workday";
        }

        return "{$this->creator} requested approval from {$this->assignee}";
    }
}
