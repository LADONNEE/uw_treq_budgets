<?php

namespace App\Factories;

use App\Models\EffortReport;
use App\Models\EffortReportAllocation;
use Carbon\Carbon;

class ReportAllocationsFactory
{
    private $clampedAllocations;
    private $effortReport;
    private $typeAllocations;
    private $subPeriodStartDates;

    public function __construct($clampedAllocations, $effortReport, $typeAllocations, $subPeriodStartDates)
    {
        $this->clampedAllocations = $clampedAllocations;
        $this->effortReport = $effortReport;
        $this->typeAllocations = $typeAllocations;
        $this->subPeriodStartDates = $subPeriodStartDates;
    }

    public static function load($effortReport)
    {
        $reportRange = ['start_at' => $effortReport->start_at, 'end_at' => $effortReport->end_at];
        $allocationsWithinRange = EffortReport::getAllocationsWithinRange($effortReport->faculty_contact_id, $reportRange);
        $clampedAllocations = self::clampAllocationsToReportPeriod($allocationsWithinRange, $reportRange, $effortReport->id);
        $typeAllocations = EffortReportAllocation::getAllocationsByType($clampedAllocations,EffortReportAllocation::TYPE_ALLOCATION);

        return new ReportAllocationsFactory(
            $clampedAllocations,
            $effortReport,
            $typeAllocations,
            array_values(self::getSubPeriodStartDates($reportRange, $typeAllocations)->toArray())
        );
    }

    public function getReportAllocationsWithoutDefaults()
    {
       return collect($this->clampedAllocations)->sortBy('start_at')->sortByDesc('type')->values();
    }

    public function getReportAllocationsWithDefaults()
    {
        $reportAllocations = $this->getNonDefaultAllocationsWithinSubperiod();
        $defaultAllocations = self::getDefaultBudgetAllocations($this->effortReport, $this->effortReport->faculty->default_budget_id ?? null, $reportAllocations, $this->subPeriodStartDates);

        if ($defaultAllocations) {
            foreach ($defaultAllocations as $allocation) {
                $reportAllocations[] = $allocation;
            }
        }

        $sortedReportAllocations = $this->array_orderby($reportAllocations, 'start_at', SORT_ASC, 'budget_id', SORT_DESC);
        return collect($sortedReportAllocations)->values();
    }

    private function getNonDefaultAllocationsWithinSubperiod()
    {
        $allocations = [];

        for ($i = 0; $i < count($this->subPeriodStartDates) - 1; ++$i) {
            foreach ($this->typeAllocations as $allocation) {
                if (Carbon::parse($allocation->start_at) <= $this->subPeriodStartDates[$i] && Carbon::parse($allocation->end_at) >= $this->subPeriodStartDates[$i]) {
                    $allocations[] = self::createNonDefaultBudgetAllocation($allocation, $this->subPeriodStartDates[$i], $this->subPeriodStartDates[$i + 1]->copy()->subDay(), $this->effortReport->id);
                }
            }
        }

        return $allocations;
    }

    public function getInvalidAllocationPeriods()
    {
        $allocations = EffortReportAllocation::getAllocationsByType($this->clampedAllocations, 'ALLOCATION');
        $invalidPeriods = [];

        for ($i = 0; $i < count($this->subPeriodStartDates) - 1; $i++) {
            $percent = self::sumEffortOfActiveAllocations($this->subPeriodStartDates[$i], $allocations);
            if ($percent > 100.0) {
                array_push($invalidPeriods, eDate($this->subPeriodStartDates[$i]));
            }
        }

        return $invalidPeriods;
    }

    private function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    public static function allocationsWithCostingTableData($allocations, $defaultFiscalPerson, $defaultBudget): array
    {
        $tableData = [];
        $fte = 0;
        $periodStart = null;
        $periodEnd = null;
        $fteRow = null;
        $unpaidFte = $allocations[0]->effortReport->type === EffortReport::PERIOD_SUMMER || !$defaultBudget;

        for($i = 0; $i < count($allocations); $i++) {
            $allocationIsDefault = $allocations[$i]->allocation_category === EffortReportAllocation::TYPE_DEFAULT;
            $allocationIsSummerHiatus = $allocations[$i]->allocation_category === EffortReportAllocation::TYPE_HIATUS;
            $allocationIsUnpaid = $allocations[$i]->allocation_category === EffortReportAllocation::TYPE_HIATUS || $allocations[$i]->allocation_category === EffortReportAllocation::TYPE_UNPAID;
            $allocationIsStandard = !$allocationIsDefault && !$allocationIsSummerHiatus && !$allocationIsUnpaid;
            $isNewPeriod = $periodStart !== $allocations[$i]->start_at && $periodEnd !== $allocations[$i]->end_at;

            if($isNewPeriod) {
                unset($fteRow);
                $fteRow = null;
                $fte = 0;
                $periodStart = $allocations[$i]->start_at;
                $periodEnd = $allocations[$i]->end_at;

                if ($i === 0) {
                    $tableData[] = self::createFteRow($allocations[$i]->start_at, $allocations[$i]->end_at, $unpaidFte);
                    $fteRow = &$tableData[count($tableData) - 1];
                }

                // Update prev range fte row
                if ($unpaidFte) {
                    if ($fteRow) {
                        $fteRow[6] = round($fte, 4);
                    }
                }

                if ($i !== 0) {
                    $tableData[] = self::createFteRow($allocations[$i]->start_at, $allocations[$i]->end_at, $unpaidFte);
                    $fteRow = &$tableData[count($tableData) - 1];
                }
            }

            if ($allocationIsStandard) {
                $fte += $allocations[$i]->allocation_percent;

                if ($unpaidFte && $fteRow) {
                    $fteRow[6] = round($fte, 4);
                }
            }

            if ($allocationIsUnpaid) {
                $budgetManager = '';
            } elseif ($allocations[$i]->budget && $allocations[$i]->budget->manager) {
                $budgetManager = $allocations[$i]->budget->manager->firstname . ' ' . $allocations[$i]->budget->manager->lastname;
            } else {
                $budgetManager = $defaultFiscalPerson;
            }

            // hiatus, default, or standard row
            $row = [
                eDate($allocations[$i]->start_at) . ' - ' . eDate($allocations[$i]->end_at),
                $allocationIsUnpaid ? '' : ($allocations[$i]->budget->budgetno ?? null),
                $allocationIsUnpaid ? '' : ($allocations[$i]->allocation_category === 'CROSS UNIT EFFORT'
                    ? $allocations[$i]->budget->non_coe_name ?? ''
                    : $allocations[$i]->budgetBiennium->name ?? ''),
                $budgetManager,
                $allocations[$i]->pca_code,
                $allocations[$i]->allocation_category,
                $allocations[$i]->allocation_percent,
                $unpaidFte && !$allocationIsStandard ? '' : 'to be calculated',
                $allocations[$i]->end_at
            ];

            $tableData[] = $row;
        }

        // loop back through table to add split
        for ($i = 0; $i < count($tableData); $i++) {
            if ($tableData[$i][1] === '' && $tableData[$i][5] === '') {
                $fte = $tableData[$i][6];
            }

            if ($tableData[$i][7] === 'to be calculated') {
                $tableData[$i][7] = $tableData[$i][6] != 0 ? round($tableData[$i][6] / ($fte / 100), 4) . '%' : '0%';
            }
        }

        return $tableData;
    }

    private static function createFteRow($startAt, $endAt, $unpaidFte)
    {
        return [
            eDate($startAt) . ' - ' . eDate($endAt),
            '',
            '',
            '',
            '',
            '',
            $unpaidFte ? 0 : 100,
            '',
            $endAt
        ];
    }

    private static function clampAllocationsToReportPeriod($allocations, $reportRange, $reportId)
    {
        $clampedAllocations = [];

        foreach ($allocations as $allocation) {
            $allocationStart = clamp($allocation->start_at, $reportRange['start_at'], $reportRange['end_at']);
            $allocationEnd = clamp($allocation->end_at, $reportRange['start_at'], $reportRange['end_at']);

            array_push($clampedAllocations, self::createNonDefaultBudgetAllocation($allocation, $allocationStart, $allocationEnd, $reportId));
        }

        return $clampedAllocations;
    }

    private static function createNonDefaultBudgetAllocation($allocation, $startDate, $endDate, $reportId)
    {
        $reportAllocation = new EffortReportAllocation();
        $reportAllocation->report_id = $reportId;
        $reportAllocation->budget_id = $allocation->budget_id;
        $reportAllocation->pca_code = $allocation->pca_code;
        $reportAllocation->allocation_percent = $allocation->allocation_percent;
        $reportAllocation->additional_pay_fixed_monthly = $allocation->additional_pay_fixed_monthly;
        $reportAllocation->type = $allocation->type;
        $reportAllocation->allocation_category = $allocation->allocation_category;
        $reportAllocation->additional_pay_category = $allocation->additional_pay_category;
        $reportAllocation->note = $allocation->note;
        $reportAllocation->start_at = $startDate;
        $reportAllocation->end_at = $endDate;

        return $reportAllocation;
    }

    private static function getDefaultBudgetAllocations($report, $defaultBudget, $allocations, $subPeriodStartDates)
    {
        $defaultAllocations = [];

        for ($i = 0; $i < count($subPeriodStartDates) - 1; $i++) {
            $percentNonDefault = self::sumEffortOfActiveAllocations($subPeriodStartDates[$i], $allocations);

            if (round($percentNonDefault, 4) >= 100.0) {
                continue;
            }

            $subPeriodEndDate = $subPeriodStartDates[$i + 1]->copy()->subDay();
            $percentDefault = 100.0 - $percentNonDefault;
            $defaultAllocations[] = self::createNewSubPeriodAllocation($report->type, $report->id, $defaultBudget, $subPeriodStartDates[$i], $subPeriodEndDate, $percentDefault);
        }

        return $defaultAllocations;
    }

    private static function getSubPeriodStartDates($reportRange, $allocations)
    {
        // include report start date and report end date + 1 (the next start date)
        $subPeriodDates = [Carbon::parse($reportRange['start_at']), Carbon::parse($reportRange['end_at'])->copy()->addDay()];

        foreach ($allocations as $allocation) {
            $start = Carbon::parse(clamp($allocation->start_at, $reportRange['start_at'], $reportRange['end_at']));
            $nextStart = Carbon::parse(clamp($allocation->end_at, $reportRange['start_at'], $reportRange['end_at']))->addDay();

            if (!in_array($start, $subPeriodDates)) {
                array_push($subPeriodDates, $start);
            }

            if (!in_array($nextStart, $subPeriodDates)) {
                array_push($subPeriodDates, $nextStart);
            }
        }

        usort($subPeriodDates, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        });

        return collect($subPeriodDates);
    }

    private static function sumEffortOfActiveAllocations($startDate, $allocations)
    {
        $allocatedPercent = 0;

        foreach ($allocations as $allocation) {
            // get allocations within date range
            if (Carbon::parse($allocation->start_at) <= $startDate && Carbon::parse($allocation->end_at) >= $startDate && $allocation->allocation_percent) {
                $allocatedPercent += $allocation->allocation_percent;
            }
        }

        return $allocatedPercent;
    }

    private static function createNewSubPeriodAllocation($reportType, $reportId, $defaultBudget, $startDate, $endDate, $percent)
    {
        if ($reportType === EffortReport::PERIOD_ACADEMIC_YEAR && !$defaultBudget) {
            $category = EffortReportAllocation::TYPE_UNPAID;
        } elseif ($reportType === EffortReport::PERIOD_SUMMER) {
            $category = EffortReportAllocation::TYPE_HIATUS;
        } else {
            $category = EffortReportAllocation::TYPE_DEFAULT;
        }

        $reportAllocation = new EffortReportAllocation();
        $reportAllocation->report_id = $reportId;
        $reportAllocation->budget_id = $reportType === EffortReport::PERIOD_SUMMER ? null : $defaultBudget;
        $reportAllocation->allocation_percent = number_format($percent, 4);
        $reportAllocation->type = EffortReportAllocation::TYPE_ALLOCATION;
        $reportAllocation->is_automatic = 1;
        $reportAllocation->allocation_category = $category;
        $reportAllocation->start_at = $startDate;
        $reportAllocation->end_at = $endDate;

        return $reportAllocation;
    }
}
