<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property string $BienniumYear
 * @property string $BudgetNbr
 * @property string $BudgetName
 * @property integer $BudgetStatus
 * @property string $BudgetType
 * @property string $BudgetClass
 * @property string $BudgetClassDesc
 * @property Carbon $EffectiveDate
 * @property string $OrgCode
 * @property string $PayrollUnitCode
 * @property integer $PrincipalInvestigatorId
 * @property string $PrincipalInvestigator
 * @property Carbon $TotalPeriodBeginDate
 * @property Carbon $TotalPeriodEndDate
 * @property float $AccountingIndirectCostRate
 * @property string $budgetno
 * @property boolean $updating
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * ---------   Relationships   ---------
 */
class UwBudget extends AbstractModel
{
    protected $table = 'uw_budgets_cache';
    protected $fillable = [
        'BienniumYear',
        'BudgetNbr',
        'BudgetName',
        'BudgetStatus',
        'BudgetType',
        'BudgetClass',
        'BudgetClassDesc',
        'EffectiveDate',
        'OrgCode',
        'PayrollUnitCode',
        'PrincipalInvestigatorId',
        'PrincipalInvestigator',
        'TotalPeriodBeginDate',
        'TotalPeriodEndDate',
        'AccountingIndirectCostRate',
        'budgetno',
        'updating',
    ];
    protected $dates = [
        'EffectiveDate',
        'TotalPeriodBeginDate',
        'TotalPeriodEndDate',
        'created_at',
        'updated_at',
    ];

    protected static $type_lookup = [
        '01' => 'General University',
        '05' => 'Grants & Contracts',
        '06' => 'Gifts & Discretionary',
        '10' => 'Aux Ed Activity / Self-Sustaining',
        '54' => 'Endowments',
    ];

    public function getBeginDate()
    {
        if ($this->TotalPeriodBeginDate) {
            return $this->TotalPeriodBeginDate;
        }
        return $this->EffectiveDate;
    }

    public function getEndDate()
    {
        if ($this->TotalPeriodEndDate) {
            return $this->TotalPeriodEndDate;
        }
        return null;
    }

    public function getStatusDescription()
    {
        return BudgetStatusNames::full($this->BudgetStatus);
    }

    public function getStatusShort()
    {
        return BudgetStatusNames::short($this->BudgetStatus);
    }

    public function getTypeDescription()
    {
        if (isset(static::$type_lookup[$this->BudgetType])) {
            return static::$type_lookup[$this->BudgetType];
        }
        return $this->BudgetType;
    }

    /**
     * Sets current field for all rows to false in preparation for new import
     */
    public static function resetCurrent()
    {
        DB::statement('UPDATE uw_budgets_cache SET updating = 1');
    }

    public function setBudgetNbrAttribute($value)
    {
        $this->setScrubbedBudgetNumber($value);
    }

    public function setBudgetnoAttribute($value)
    {
        $this->setScrubbedBudgetNumber($value);
    }

    private function setScrubbedBudgetNumber($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        $value = substr($value, 0, 6);
        if (strlen($value) === 0) {
            $this->attributes['BudgetNbr'] = null;
            $this->attributes['budgetno'] = null;
            return;
        }
        $this->attributes['BudgetNbr'] = $value;
        $this->attributes['budgetno'] = substr($value, 0, 2) . '-' . substr($value, 2);
    }
}
