<?php
namespace App\Mail;

class ApprovalAskedMail extends BaseApprovalMailable
{
    protected function makeIntro(): string
    {
        return 'An Effort Report is awaiting your approval.';
    }

    protected function makeSubject($title): string
    {
        return "Faculty Effort Approval Needed: {$title}";
    }
}
