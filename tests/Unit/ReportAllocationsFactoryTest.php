<?php

namespace Tests\Unit;

use App\Factories\ReportAllocationsFactory;
use App\Models\EffortReport;
use App\Models\EffortReportAllocation;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ReportAllocationsFactoryTest extends TestCase
{
    protected $defaultBudget;
    protected $reportStart;
    protected $reportEnd;
    protected $effortReport;

    protected function setup(): void
    {
        $this->defaultBudget = '06-0850';
        $this->reportStart = Carbon::parse('2020-09-16');
        $this->reportEnd = Carbon::parse('2021-06-15');
        $this->effortReport = self::createEffortReport(5000, $this->reportStart, $this->reportEnd);
    }

    /** @test */
    public function it_doesnt_make_unnecessary_modifications()
    {
        $allocationStart = Carbon::parse('2020-09-16');
        $allocationEnd = Carbon::parse('2021-06-15');
        $allocations = [self::createAllocation(100, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(1, $results);
        $this->assertEquals(100, $results[0]->allocation_percent);
        $this->assertEquals(Carbon::parse('2020-09-16'), $results[0]->start_at);
        $this->assertEquals(Carbon::parse('2021-06-15'), $results[0]->end_at);
    }

    /** @test */
    public function it_clamps_start_date_when_allocation_start_is_before_report_start()
    {
        $allocationStart = Carbon::parse('2020-09-01');
        $allocationEnd = Carbon::parse('2021-06-15');
        $allocations = [self::createAllocation(100, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertEquals(Carbon::parse('2020-09-16'), $results[0]->start_at);
    }

    /** @test */
    public function it_clamps_end_date_when_allocation_end_is_after_report_end()
    {
        $allocationStart = Carbon::parse('2020-09-01');
        $allocationEnd = Carbon::parse('2021-06-30');
        $allocations = [self::createAllocation(100, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertEquals(Carbon::parse('2021-06-15'), $results[0]->end_at);
    }


    /** @test */
    public function it_adds_correct_number_of_default_allocations_when_two_allocations_have_the_same_allocations_percent_back_to_back()
    {
        $allocationStart1 = Carbon::parse('2020-09-16');
        $allocationEnd1 = Carbon::parse('2020-09-29');

        $allocationStart2 = Carbon::parse('2020-09-30');
        $allocationEnd2 = Carbon::parse('2021-06-15');

        $allocations = [
            self::createAllocation(25, $allocationStart1, $allocationEnd1),
            self::createAllocation(25, $allocationStart2, $allocationEnd2)
        ];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(4, $results);
    }

    /** @test */
    public function it_gets_correct_default_budget_amount_when_allocation_covers_total_report_period()
    {
        $allocationStart = Carbon::parse('2020-09-16');
        $allocationEnd = Carbon::parse('2021-06-15');
        $allocations = [self::createAllocation(50, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(2, $results);
        $this->assertEquals(50, $results[0]->allocation_percent);
        $this->assertEquals(50, $results[1]->allocation_percent);
    }

    /** @test */
    public function it_gets_correct_default_budget_amount_when_allocation_occurs_in_the_beginning_of_the_report_period()
    {
        $allocationStart = Carbon::parse('2020-09-16');
        $allocationEnd = Carbon::parse('2020-12-01');
        $allocations = [self::createAllocation(50, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(3, $results);
        $this->assertEquals(50, $results[0]->allocation_percent);
        $this->assertEquals(50, $results[1]->allocation_percent);
        $this->assertEquals(100, $results[2]->allocation_percent);
    }

    /** @test */
    public function it_gets_correct_default_budget_amount_when_allocation_occurs_in_the_middle_of_the_report_period()
    {
        $allocationStart = Carbon::parse('2020-12-01');
        $allocationEnd = Carbon::parse('2021-02-01');
        $allocations = [self::createAllocation(50, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(4, $results);
        $this->assertEquals(100, $results[0]->allocation_percent);
        $this->assertEquals(50, $results[1]->allocation_percent);
        $this->assertEquals(50, $results[2]->allocation_percent);
        $this->assertEquals(100, $results[3]->allocation_percent);
    }

    /** @test */
    public function it_gets_correct_default_budget_amount_when_allocation_occurs_in_the_end_of_the_report_period()
    {
        $allocationStart = Carbon::parse('2021-01-15');
        $allocationEnd = Carbon::parse('2021-06-15');
        $allocations = [self::createAllocation(50, $allocationStart, $allocationEnd)];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(3, $results);
        $this->assertEquals(100, $results[0]->allocation_percent);
        $this->assertEquals(50, $results[1]->allocation_percent);
        $this->assertEquals(50, $results[2]->allocation_percent);
    }

    /** @test */
    public function allocation_dates_are_correct_with_multiple_allocations()
    {
        $allocationStart1 = Carbon::parse('2019-01-15');
        $allocationEnd1 = Carbon::parse('2020-10-01');

        $allocationStart2 = Carbon::parse('2020-11-01');
        $allocationEnd2 = Carbon::parse('2021-04-15');

        $allocationStart3 = Carbon::parse('2021-01-01');
        $allocationEnd3 = Carbon::parse('2022-06-15');

        $allocations = [
            self::createAllocation(50, $allocationStart1, $allocationEnd1),
            self::createAllocation(10, $allocationStart2, $allocationEnd2),
            self::createAllocation(05, $allocationStart3, $allocationEnd3),
        ];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(10, $results);

        $this->assertEquals(Carbon::parse('2020-09-16'), $results[0]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-01'), $results[0]->end_at);

        $this->assertEquals(Carbon::parse('2020-09-16'), $results[1]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-01'), $results[1]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-02'), $results[2]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-31'), $results[2]->end_at);

        $this->assertEquals(Carbon::parse('2020-11-01'), $results[3]->start_at);
        $this->assertEquals(Carbon::parse('2020-12-31'), $results[3]->end_at);

        $this->assertEquals(Carbon::parse('2020-11-01'), $results[4]->start_at);
        $this->assertEquals(Carbon::parse('2020-12-31'), $results[4]->end_at);

        $this->assertEquals(Carbon::parse('2021-01-01'), $results[5]->start_at);
        $this->assertEquals(Carbon::parse('2021-04-15'), $results[5]->end_at);

        $this->assertEquals(Carbon::parse('2021-01-01'), $results[6]->start_at);
        $this->assertEquals(Carbon::parse('2021-04-15'), $results[6]->end_at);

        $this->assertEquals(Carbon::parse('2021-01-01'), $results[7]->start_at);
        $this->assertEquals(Carbon::parse('2021-04-15'), $results[7]->end_at);

        $this->assertEquals(Carbon::parse('2021-04-16'), $results[8]->start_at);
        $this->assertEquals(Carbon::parse('2021-06-15'), $results[8]->end_at);

        $this->assertEquals(Carbon::parse('2021-04-16'), $results[9]->start_at);
        $this->assertEquals(Carbon::parse('2021-06-15'), $results[9]->end_at);
    }

    /** @test */
    public function it_handles_overlapping_allocations_by_splitting_them_into_new_allocations()
    {
        $allocationStart1 = Carbon::parse('2019-08-01');
        $allocationEnd1 = Carbon::parse('2020-10-01');

        $allocationStart2 = Carbon::parse('2020-10-01');
        $allocationEnd2 = Carbon::parse('2020-12-01');

        $allocations = [
            self::createAllocation(20, $allocationStart1, $allocationEnd1),
            self::createAllocation(40, $allocationStart2, $allocationEnd2),
        ];

        $results = ReportAllocationsFactory::getReportAllocationsWithDefaults($this->defaultBudget, $this->effortReport, $allocations);

        $this->assertCount(8, $results);

        $this->assertEquals(Carbon::parse('2020-09-16'), $results[0]->start_at);
        $this->assertEquals(Carbon::parse('2020-09-30'), $results[0]->end_at);

        $this->assertEquals(Carbon::parse('2020-09-16'), $results[1]->start_at);
        $this->assertEquals(Carbon::parse('2020-09-30'), $results[1]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-01'), $results[2]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-01'), $results[2]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-01'), $results[3]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-01'), $results[3]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-01'), $results[4]->start_at);
        $this->assertEquals(Carbon::parse('2020-10-01'), $results[4]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-02'), $results[5]->start_at);
        $this->assertEquals(Carbon::parse('2020-12-01'), $results[5]->end_at);

        $this->assertEquals(Carbon::parse('2020-10-02'), $results[6]->start_at);
        $this->assertEquals(Carbon::parse('2020-12-01'), $results[6]->end_at);

        $this->assertEquals(Carbon::parse('2020-12-02'), $results[7]->start_at);
        $this->assertEquals(Carbon::parse('2021-06-15'), $results[7]->end_at);
    }

    private static function createAllocation($percent, $start, $end)
    {
        $allocation = new EffortReportAllocation();
        $allocation->type = EffortReportAllocation::TYPE_ALLOCATION;
        $allocation->allocation_percent = $percent;
        $allocation->start_at = $start;
        $allocation->end_at = $end;

        return $allocation;
    }

    private static function createEffortReport($reportId, $startDate, $endDate)
    {
        $effortReport = new EffortReport();
        $effortReport->id = $reportId;
        $effortReport->start_at = $startDate;
        $effortReport->end_at = $endDate;

        return $effortReport;
    }
}
