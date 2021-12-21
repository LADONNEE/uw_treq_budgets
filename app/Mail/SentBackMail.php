<?php
namespace App\Mail;

class SentBackMail extends BaseEffortReportMailable
{
    protected function makeIntro(): string
    {
        return 'The following Effort Report was sent back. You may revise and re-submit it.';
    }

    protected function makeSubject($title): string
    {
        return "Faculty Effort Report Sent Back: {$title}";
    }
}
