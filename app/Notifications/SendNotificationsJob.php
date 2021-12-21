<?php
namespace App\Notifications;

use App\Models\Setting;
use App\Utilities\MailSender;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class SendNotificationsJob
{
    private $cutoffAfter;
    private $cutoffBefore;
    private $sender;
    private $tasks = [
        ApprovalNotifications::class,
        CompleteNotifications::class,
        SentBackNotifications::class,
        RevisitNotifications::class,
    ];

    /**
     * @var Carbon
     */
    private $startedAt;

    /**
     * @var Carbon
     */
    private $endedAt;

    public function __construct(MailSender $sender, $sendAll = false)
    {
        if ($sendAll) {
            $this->cutoffAfter = now()->subMonths(3);
            $this->cutoffBefore = now()->addHours(1);
        } else {
            $this->cutoffAfter = now()->subMonths(1);
            $this->cutoffBefore = now()->subMinutes(15);
        }

        $this->sender = $sender;
    }

    public function report()
    {
        $out = [];
        foreach ($this->tasks as $classname) {
            $task = new $classname($this->sender, $this->cutoffAfter, $this->cutoffBefore);
            $out = array_merge($out, $task->getPendingHeaders());
        }
        return $out;
    }

    public function run()
    {
        $this->startedAt = now();
        foreach ($this->tasks as $classname) {
            $task = new $classname($this->sender, $this->cutoffAfter, $this->cutoffBefore);
            $task->run();
        }
        $this->endedAt = now();

        $this->logMailWasSent();
    }

    private function logMailWasSent()
    {
        $log = Setting::firstOrNew(['name' => 'email-sent']);
        $started = $this->startedAt->format('D, M j, Y \a\t g:i A');
        $duration = $this->startedAt->diffForHumans($this->endedAt,CarbonInterface::DIFF_ABSOLUTE);
        $log->value = "{$started}, took {$duration}";
        $log->changed_by = 0;
        $log->save();
    }
}
