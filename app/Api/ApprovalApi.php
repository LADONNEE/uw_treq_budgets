<?php

namespace App\Api;

use App\Auth\User;
use App\Models\Approval;
use App\Models\EffortReport;

class ApprovalApi
{
    private $report;
    private $effortReport;
    private $isDone;
    private $user;

    public function __construct(EffortReport $effortReport, User $user = null)
    {
        $this->effortReport = $effortReport;
        $this->isDone = ($effortReport->isComplete() || $effortReport->isCanceled());
        $this->user = $user;
    }

    public function getReport()
    {
        $this->lazyLoad();
        return $this->report;
    }

    public function lazyLoad()
    {
        if ($this->report === null) {
            $this->load();
        }
    }

    public function load()
    {
        if (!$this->effortReport->isComplete()) {
            $results = Approval::where('report_id', $this->effortReport->id)
                ->orderBy('response')
                ->orderBy('sequence')
                ->with(['assignee', 'completer'])
                ->get();
        } else {
            $results = Approval::where('report_id', $this->effortReport->id)
                ->where('response', '!=', Approval::RESPONSE_PENDING)
                ->orderBy('sequence')
                ->with(['assignee', 'completer'])
                ->get();
        }

        $out = [];
        foreach ($results as $task) {
            $out[] = new ApprovalApiItem($task, $this->user);
        }
        $this->report = collect($out);
    }

    public function needsApprovalFrom($personId)
    {
        if ($this->isDone) {
            return false;
        }
        $this->lazyLoad();
        return $this->report->contains(function ($task, $key) use ($personId) {
            return (!$task->hasResponse && $task->response_by === $personId && !$task->isVoid);
        });
    }
}
