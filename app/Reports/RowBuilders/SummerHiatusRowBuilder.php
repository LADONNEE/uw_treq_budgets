<?php

namespace App\Reports\RowBuilders;

use App\Core\RowBuilder\AbstractRowBuilder;
use App\Models\Contact;
use App\Models\EffortReport;
use App\Models\EffortReportAllocation;
use Carbon\Carbon;

class SummerHiatusRowBuilder extends AbstractRowBuilder
{
    protected $modelClass = Contact::class;

    protected function getData()
    {
        return [
            $this->model->lastname . ', ' . $this->model->firstname,
            $this->model->uwnetid ?? $this->model->effortReport->faculty->uwnetid,
            $this->model->effortReport ? $this->model->effortReport->faculty->employeeid : $this->model->employeeid,
            ($this->model->effortReport
                && Carbon::parse($this->model->start_at)->toDateString() == $this->model->effortReport->start_at
                && Carbon::parse($this->model->end_at)->toDateString() == $this->model->effortReport->end_at)
                || !$this->model->effortReport
                ? 'true' : '',
            $this->model->effortReport && $this->model->effortReport->stage === EffortReport::STAGE_APPROVED ? 'true' : '',
            $this->model->effortReport ? Carbon::parse($this->model->start_at)->toDateString() : '',
            $this->model->effortReport ? Carbon::parse($this->model->end_at)->toDateString() : '',
        ];
    }

    protected function getHeaders()
    {
        return [
            'Faculty',
            'UW NetID',
            'Employee ID',
            'Unpaid Full Summer',
            'Approved Effort Report',
            'Start',
            'End',
        ];
    }

}
