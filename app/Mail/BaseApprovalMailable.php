<?php
namespace App\Mail;

use App\Models\Approval;
use Carbon\Carbon;

abstract class BaseApprovalMailable extends BaseEffortReportMailable
{
    protected $approval;

    public function __construct(Approval $approval)
    {
        parent::__construct($approval->effortReport);
        $this->approval = $approval;
    }

    public function getMetadata(): array
    {
        return [
            'approval_id' => $this->approval->id,
        ];
    }

    public function wasSent(Carbon $sentAt): void
    {
        $this->approval->notified_at = $sentAt;
        $this->approval->save();
    }
}
