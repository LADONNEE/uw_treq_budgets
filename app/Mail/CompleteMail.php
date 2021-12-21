<?php
namespace App\Mail;

class CompleteMail extends BaseEffortReportMailable
{

    protected function makeIntro(): string
    {
        return 'The following Effort Report is now complete.';
    }

    protected function makeSubject($title): string
    {
        return "Faculty Effort Report Complete: {$title}";
    }
}
