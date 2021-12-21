<?php
namespace App\Mail;

use Carbon\Carbon;

class RevisitMail extends BaseEffortReportMailable
{

    protected function makeIntro(): string
    {
        return 'The following Effort Report is ready to be revisited.';
    }

    protected function makeSubject($title): string
    {
        return "Revisit Faculty Effort Report: {$title}";
    }

    public function wasSent(Carbon $sentAt): void
    {
        $this->effortReport->revisit_notified_at = $sentAt;
        $this->effortReport->save();
    }
}
