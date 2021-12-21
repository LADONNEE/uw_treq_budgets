<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEffortReportAllocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
           ALTER TABLE effort_report_allocations
            ADD (
                CONSTRAINT CK__effort_report_allocations__allocation_percent__range
                CHECK (allocation_percent >= 0.0 AND allocation_percent <= 100.0),

                CONSTRAINT CK__effort_report_allocations__additional_pay__range
                CHECK (additional_pay_fixed_monthly >= 0),

                CONSTRAINT CK__effort_report_allocations__single_cost_type
                CHECK (
                    (allocation_percent IS NULL AND additional_pay_fixed_monthly IS NOT NULL) OR
                    (allocation_percent IS NOT NULL AND additional_pay_fixed_monthly IS NULL)
                ),

                CONSTRAINT CK__effort_report_allocations__start_at_earlier_than_end_at
                CHECK (DATEDIFF(start_at, end_at) <= 0)
            );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
