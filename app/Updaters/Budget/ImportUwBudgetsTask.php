<?php

namespace App\Updaters\Budget;

use App\Edw\BudgetDataSource;
use App\Models\UwBudget;
use App\Updaters\EdwParser;
use Illuminate\Support\Facades\DB;

/**
 * Query budget data from UW EDW connection and save records locally in uw_budgets_cache
 */
class ImportUwBudgetsTask
{
    /**
     * @var BudgetDataSource
     */
    protected $edw;
    /**
     * @var EdwParser
     */
    protected $parser;

    public function __construct(BudgetDataSource $edw, EdwParser $parser)
    {
        $this->edw = $edw;
        $this->parser = $parser;
    }

    public function run()
    {
        $this->setUpdating();
        $results = $this->edw->getCollegeBudgets();
        foreach ($results as $row) {
            $data = $this->parseRow($row);
            $budget = UwBudget::firstOrNew([
                'BienniumYear' => $data['BienniumYear'],
                'BudgetNbr' => $data['BudgetNbr'],
            ]);
            $budget->fill($data);
            $budget->updating = 0;
            $budget->save();
        }

        $this->doDeletes();
    }

    public function parseRow($row)
    {
        $out = [];
        foreach ($row as $index => $value) {
            $out[$index] = $this->parser->string($value);
        }
        $out['EffectiveDate'] = $this->parser->dateYmd($row['EffectiveDate']);
        $out['TotalPeriodBeginDate'] = $this->parser->dateYmd($row['TotalPeriodBeginDate']);
        $out['TotalPeriodEndDate'] = $this->parser->dateYmd($row['TotalPeriodEndDate']);
        $out['FoodApprovalInd'] = $this->parser->integer($row['FoodApprovalInd']);
        if ($out['FoodApprovalInd'] === null) {
            $out['FoodApprovalInd'] = 0;
        }
        return $out;
    }

    private function setUpdating()
    {
        DB::table('uw_budgets_cache')->update(['updating' => 1]);
    }

    private function doDeletes()
    {
        DB::table('uw_budgets_cache')
            ->where('updating', 1)
            ->delete();
    }
}
