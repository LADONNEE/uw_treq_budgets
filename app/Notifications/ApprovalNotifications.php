<?php
namespace App\Notifications;

use App\Mail\ApprovalAskedMail;
use App\Models\Approval;

class ApprovalNotifications extends BaseNotification
{
    protected function todo()
    {
        return Approval::where('updated_at', '>=', $this->cutoffAfter)
            ->where('created_at', '<', $this->cutoffBefore)
            ->whereNotNull('assigned_to_contact_id')
            ->whereNull('notified_at')
            ->whereNull('responded_at')
            ->pluck('id')
            ->all();
    }

    protected function getPendingHeaderItem($id)
    {
        $approval = Approval::find($id);

        if (!$this->shouldSendApproval($approval)) {
            return null;
        }

        $email = ($approval->assignee && $approval->assignee->uwnetid) ? "{$approval->assignee->uwnetid}@uw.edu" : "(missing email)";

        return new PendingEmailHeader($approval->effortReport, $email, 'Need Approval');
    }

    public function notifyItem($id)
    {
        $approval = Approval::find($id);

        if (!$this->shouldSendApproval($approval)) {
            return;
        }

        $email = ($approval->assignee && $approval->assignee->uwnetid) ? "{$approval->assignee->uwnetid}@uw.edu" : "(missing email)";

        $this->sender->send($approval->effortReport->id, $email, new ApprovalAskedMail($approval));
    }
}
