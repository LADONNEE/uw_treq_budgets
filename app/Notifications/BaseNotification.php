<?php
namespace App\Notifications;

use App\Models\Approval;
use App\Models\EffortReport;
use App\Utilities\MailSender;
use Carbon\Carbon;

abstract class BaseNotification
{
    protected $cutoffAfter;
    protected $cutoffBefore;
    protected $sender;

    public function __construct(MailSender $sender, Carbon $cutoffAfter, Carbon $cutoffBefore)
    {
        $this->cutoffAfter = $cutoffAfter;
        $this->cutoffBefore = $cutoffBefore;
        $this->sender = $sender;
    }

    /**
     * Provide a header object for a single notification item
     * @param mixed $item
     * @return PendingEmailHeader|null
     */
    abstract protected function getPendingHeaderItem($item);

    /**
     * Provide a list of items that need notifications sent
     * @return mixed
     */
    abstract protected function todo();

    /**
     * Process single item provided by todo() method
     * @param mixed $item
     * @return mixed
     */
    abstract public function notifyItem($item);

    /**
     * Return brief headers for each to-do item
     * Support preview of pending email messages
     * @return PendingEmailHeader[]
     */
    public function getPendingHeaders()
    {
        $out = [];
        $items = $this->todo();

        foreach ($items as $id) {
            $h = $this->getPendingHeaderItem($id);
            if ($h instanceof PendingEmailHeader) {
                $out[] = $h;
            }
        }
        return $out;
    }

    public function run()
    {
        $items = $this->todo();

        foreach ($items as $id) {
            $this->notifyItem($id);
        }
    }

    protected function shouldSendReport($effortReport)
    {
        if (!$effortReport instanceof EffortReport) {
            return false;
        }

        if ($effortReport->isCanceled()) {
            return false;
        }

        if ((!$effortReport->needsRevisit() && $effortReport->notified_at)
            || ($effortReport->needsRevisit() && $effortReport->revisit_notified_at)) {
            return false;
        }

        return true;
    }

    protected function shouldSendApproval($approval)
    {
        if (!$approval instanceof Approval) {
            return false;
        }

        if ($approval->notified_at) {
            return false;
        }

        if ($approval->responded_at) {
            return false;
        }

        if (!$approval->effortReport instanceof EffortReport) {
            return false;
        }

        if(!$approval->isReadyToApprove()) {
            return false;
        }

        if ($approval->effortReport->isComplete() || $approval->effortReport->isCanceled()) {
            return false;
        }

        return true;
    }
}
