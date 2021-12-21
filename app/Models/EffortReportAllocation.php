<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * @property integer $id
 * @property integer $report_id
 * @property string $budget_id
 * @property string $pca_code
 * @property string $firstname
 * @property string $lastname
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property float $allocation_percent
 * @property float $additional_pay_fixed_monthly
 * @property string $type
 * @property boolean $is_automatic
 * @property string $allocation_category
 * @property string $additional_pay_category
 * @property string $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * ----------   Relationships   ----------
 * @property Budget $budget
 * @property EffortReport $effortReport
 *
 */
class EffortReportAllocation extends Model
{
    protected $fillable = [
        'report_id',
        'budget_id',
        'pca_code',
        'firstname',
        'lastname',
        'start_at',
        'end_at',
        'allocation_percent',
        'additional_pay_fixed_monthly',
        'type',
        'is_automatic',
        'allocation_category',
        'additional_pay_category',
        'note',
    ];

    const TYPE_ALLOCATION = 'ALLOCATION';
    const TYPE_ADDITIONAL_PAY = 'ADDITIONAL PAY';
    const TYPE_DEFAULT = 'DEFAULT';
    const TYPE_HIATUS = 'UNPAID HIATUS';
    const TYPE_UNPAID = 'UNPAID';

    public static $allocationCategories = [
        'STATE LINE' => 'State Line',
        'GRANT/RESEARCH' => 'Grant/Research',
        'SELF SUSTAINING' => 'Self Sustaining',
        'CROSS UNIT EFFORT' => 'Cross Unit Effort',
    ];

    public static $additionalPayCategories = [
        'ADS' => 'ADS - Administrative Supplement',
        'ENS' => 'ENS - Endowed Supplement',
        'TPS' => 'TPS - Temporary Pay Supplement',
    ];

    public function budget()
    {
        return $this->hasOne(Budget::class, 'id', 'budget_id');
    }

    public function budgetBiennium()
    {
        return $this->hasOne(BudgetBiennium::class, 'budget_id', 'budget_id');
    }

    public function effortReport()
    {
        return $this->hasOne(EffortReport::class, 'id', 'report_id');
    }

    public static function getAllocationsByType($allocations, $type) {
        $allocationsByType = [];

        foreach ($allocations as $allocation) {
            if ($allocation->type === $type) {
                array_push($allocationsByType, $allocation);
            }
        }

        return $allocationsByType;
    }

    public static function getApprovedReportAllocationsWithinRange($reportRange)
    {
        return EffortReportAllocation::select('effort_report_allocations.*')
            ->join('effort_reports', 'effort_reports.id', '=', 'report_id')
            ->join('contacts', 'contacts.id', '=', 'effort_reports.faculty_contact_id')
            ->where('effort_report_allocations.is_automatic', '=', 0)
            ->where('effort_report_allocations.start_at', '<=', $reportRange['end_at'])
            ->where('effort_report_allocations.end_at', '>=', $reportRange['start_at'])
            ->where('effort_reports.stage', '=', EffortReport::STAGE_APPROVED)
            ->orderBy('contacts.lastname')
            ->with(['effortReport', 'effortReport.faculty'])
            ->get();
    }
}
