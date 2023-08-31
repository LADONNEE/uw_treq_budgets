<?php

namespace App\Http\Controllers;

use App\Notifications\SendNotificationsJob;
use App\Utilities\MailSender;

class PendingEmail
{
    public function __invoke(MailSender $sender)
    {
        $job = new SendNotificationsJob($sender, true);
        $report = $job->report();
        $last = setting('email-sent');
        return view('pending-email.index', compact('report', 'last'));
    }
}
