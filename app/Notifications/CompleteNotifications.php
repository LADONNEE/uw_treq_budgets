<?php
namespace App\Notifications;

use App\Mail\CompleteMail;
use App\Models\EffortReport;

class CompleteNotifications extends BaseNotification
{
    protected function todo()
    {
        return EffortReport::where('updated_at', '>=', $this->cutoffAfter)
            ->where('updated_at', '<', $this->cutoffBefore)
            ->where('stage', EffortReport::STAGE_APPROVED)
            ->whereNull('notified_at')
            ->pluck('id')
            ->all();
    }

    protected function getPendingHeaderItem($id)
    {
        $effortReport = EffortReport::find($id);

        if (!$this->shouldSendReport($effortReport)) {
            return null;
        }

        return new PendingEmailHeader($effortReport, "{$effortReport->creator->uwnetid}@uw.edu", 'Effort Report Complete');
    }

    public function notifyItem($id)
    {
        $effortReport = EffortReport::find($id);

        if (!$this->shouldSendReport($effortReport)) {
            return;
        }

        $this->sender->send($effortReport->id, "{$effortReport->creator->uwnetid}@uw.edu", new CompleteMail($effortReport));
    }
}
