<?php

namespace App\Models;

class AllocationsWithCostingTableData
{
    public $period;
    public $budgetno;
    public $budgetName;
    public $budgetManager;
    public $pca;
    public $category;
    public $effort;
    public $split;
    public $calculated;
    public $end_at;

    public function __construct(array $allocations = [])
    {
        $this->period = $allocations[0];
        $this->budgetno = $allocations[1];
        $this->budgetName = $allocations[2];
        $this->budgetManager = $allocations[3];
        $this->pca = $allocations[4];
        $this->category = $allocations[5];
        $this->effort = $allocations[6];
        $this->split = $allocations[7];
        $this->calculated = $allocations[8];
    }
}
