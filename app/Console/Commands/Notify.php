<?php
namespace App\Console\Commands;

use App\Notifications\SendNotificationsJob;
use App\Utilities\MailSender;
use Illuminate\Console\Command;

class Notify extends Command
{
    protected $signature = 'notify {--all : extend created cutoff to 6 months}';

    protected $description = 'Send pending email messages created in last 48 hours';

    public function handle(MailSender $sender)
    {
        $cutoff = $this->option('all') ? now()->subMonths(6) : null;

        $job = new SendNotificationsJob($sender, $cutoff);
        $job->run();
    }
}
