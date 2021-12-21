<?php
namespace App\Notifications;

use App\Mail\RevisitMail;
use App\Models\EffortReport;

class RevisitNotifications extends BaseNotification
{
    protected function todo()
    {
        return EffortReport::where('updated_at', '>=', $this->cutoffAfter)
            ->where('created_at', '<', $this->cutoffBefore)
            ->whereNotNull('revisit_at')
            ->where('revisit_at', '<=', now())
            ->whereNull('revisit_notified_at')
            ->pluck('id')
            ->all();
    }

    protected function getPendingHeaderItem($id)
    {
        $effortReport = EffortReport::find($id);

        if (!$this->shouldSendReport($effortReport)) {
            return null;
        }

        return new PendingEmailHeader($effortReport, "coepay@uw.edu", 'Revisit Effort Report');
    }

    public function notifyItem($id)
    {
        $effortReport = EffortReport::find($id);

        if (!$this->shouldSendReport($effortReport)) {
            return;
        }

        $this->sender->send($effortReport->id, "coepay@uw.edu", new RevisitMail($effortReport));
    }
}
