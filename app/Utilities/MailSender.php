<?php
namespace App\Utilities;

use App\Contracts\ProvidesMetadata;
use App\Contracts\RecordsMailSent;
use App\Models\MailLog;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailSender
{
    const EMAIL_RFC2822 = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    public function send($report_id, $to, Mailable $mailable): void
    {
        if (!$this->validEmail($to)) {
            $this->log($mailable, $report_id, $to, 'invalid To: email address');
            return;
        }

        try {
            Mail::to($to)->send($mailable);
        } catch (\Exception $e) {
            $this->log($mailable, $report_id, $to, $e);
            return;
        }

        if ($mailable instanceof RecordsMailSent) {
            $mailable->wasSent(now());
        }

        $this->log($mailable, $report_id, $to);
    }

    public function validEmail($email): bool
    {
        if (!is_string($email) || strlen($email) === 0) {
            return false;
        }
        return (boolean) preg_match(self::EMAIL_RFC2822, $email);
    }

    private function log(Mailable $mailable, $report_id, $to, $error = null)
    {
        if ($error instanceof \Exception) {
            $e = $error;
            $error = $e->getMessage()
                . "\nin " . $e->getFile()
                . ' line ' . $e->getLine()
                . "\n\n" . $e->getTraceAsString();
        }
        $log = new MailLog([
            'effort_report_id' => $report_id,
            'mailable' => get_class($mailable),
            'email' => $to,
            'attempted_at' => now(),
            'result' => ($error) ? 'error' : 'sent',
            'error' => $error,
        ]);
        if ($mailable instanceof ProvidesMetadata) {
            $log->setMetadata($mailable->getMetadata());
        }
        $log->save();

        if (isset($e) && config('env') !== 'production') {
            throw $e;
        }
    }
}
