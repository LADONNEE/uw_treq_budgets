<?php
namespace App\Reports\RowBuilders;

use App\Core\RowBuilder\AbstractRowBuilder;
use App\Models\EffortReportAllocation;

class ReportAllocationRowBuilder extends AbstractRowBuilder
{
    protected $modelClass = EffortReportAllocation::class;

    protected function getData()
    {
        return [
            eLastFirst($this->model->effortReport->faculty->person_id),
            $this->model->effortReport->faculty->uwnetid,
            $this->model->effortReport->faculty->is_80_20 === 1 ? 'yes' : '',
            $this->model->effortReport->faculty->employeeid,
            $this->model->budget ? ' ' . $this->model->budget->budgetno : '',
            $this->model->pca_code,
            $this->model->effortReport->faculty->getFiscalPersonName(),
            $this->model->start_at,
            $this->model->end_at,
            $this->model->type,
            $this->model->allocation_percent,
            $this->model->additional_pay_fixed_monthly,
            $this->model->allocation_category,
            $this->model->additional_pay_category,
            $this->model->note,
            $this->model->created_at,
            $this->model->updated_at,
        ];
    }

    protected function getHeaders()
    {
        return [
            'Faculty',
            'UW NetID',
            '80/20',
            'Employee ID',
            'Budget No',
            'PCA Code',
            'Finance Manager',
            'Start',
            'End',
            'Type',
            'Percent',
            'Dollar Amount',
            'Allocation Category',
            'Add. Pay Category',
            'Note',
            'Created',
            'Updated',
        ];
    }

}
