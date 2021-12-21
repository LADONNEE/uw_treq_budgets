<?php
namespace App\Listeners;

use App\Events\EffortReportUpdated;
use App\Workflows\EffortReportWorkflow;

class UpdateEffortReportWorkflow
{
    private $workflow;

    public function __construct(EffortReportWorkflow $workflow)
    {
        $this->workflow = $workflow;
    }

    public function handle(EffortReportUpdated $event)
    {
        $this->workflow->update($event->effortReport);
    }
}
