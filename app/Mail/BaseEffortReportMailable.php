<?php
namespace App\Mail;

use App\Contracts\ProvidesMetadata;
use App\Contracts\RecordsMailSent;
use App\Models\Contact;
use App\Models\EffortReport;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;

abstract class BaseEffortReportMailable extends Mailable implements ProvidesMetadata, RecordsMailSent
{
    protected $effortReport;

    public function __construct(EffortReport $effortReport)
    {
        $this->effortReport = $effortReport;
    }

    /**
     * Provide email subject line
     * Optionally using the provided Project $title
     * @param string $title
     * @return string
     */
    abstract protected function makeSubject($title): string;

    /**
     * First line to be included in email body
     * @return string
     */
    protected function makeIntro(): string
    {
        return 'Your review and response is needed on this effort report.';
    }

    /**
     * Blade template name for HTML version of this email
     * example: return 'email.task-default';
     * @return string
     */
    protected function htmlView(): string
    {
        return 'budget.effort.email.email-layout';
    }

    /**
     * Blade template name for text only version of this email
     * @return string
     */
    protected function textView(): string
    {
        return $this->htmlView() . '-text';
    }

    public function build()
    {
        if ($this->effortReport) {
            $title = $this->effortReport->title();
        } else {
            $title = "Effort Report for {$this->effortReport->faculty->firstname} {$this->effortReport->faculty->lastname}";
        }
        $mailable = $this->subject($this->makeSubject($title))
            ->view($this->htmlView())
            ->text($this->textView())
            ->with([
                'intro' => $this->makeIntro(),
                'projectTitle' => $title,
                'appName' => config('app.name'),
                'url' => route('effort-report-show', [$this->effortReport->faculty_contact_id, $this->effortReport->id]),
                'reportType' => 'Effort Report',
                'submitted' => eDate($this->effortReport->created_at, 'D, M j, Y \a\t g:i A'),
            ]);

        $cc = $this->ccPerson();
        if ($cc && $cc->uwnetid) {
            $mailable->cc($cc->uwnetid . '@' . config('custom.scl_email_domain'), eFirstLast($cc));
        }

        return $mailable;
    }

    public function getMetadata(): array
    {
        return [];
    }

    public function wasSent(Carbon $sentAt): void
    {
        $this->effortReport->notified_at = $sentAt;
        $this->effortReport->save();
    }

    /**
     * Optionally return a Person object to be CC'd on this email
     * @return Contact|null
     */
    protected function ccPerson(): ?Contact
    {
        return null;
    }
}
