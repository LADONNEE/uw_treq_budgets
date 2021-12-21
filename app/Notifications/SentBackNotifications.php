<?php
namespace App\Notifications;

use App\Mail\SentBackMail;
use App\Models\EffortReport;

class SentBackNotifications extends BaseNotification
{
    protected function todo()
    {
        return EffortReport::where('updated_at', '>=', $this->cutoffAfter)
            ->where('updated_at', '<', $this->cutoffBefore)
            ->where('stage', EffortReport::STAGE_SENT_BACK)
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

        return new PendingEmailHeader($effortReport, "{$effortReport->creator->uwnetid}@uw.edu", 'Effort Report Sent Back');
    }

    public function notifyItem($id)
    {
        $effortReport = EffortReport::find($id);

        if (!$this->shouldSendReport($effortReport)) {
            return;
        }

        $this->sender->send($effortReport->id, "{$effortReport->creator->uwnetid}@uw.edu", new SentBackMail($effortReport));
    }
}
