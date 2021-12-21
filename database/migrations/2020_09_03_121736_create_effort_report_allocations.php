<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEffortReportAllocations extends Migration
{
    public function up()
    {
        Schema::create('effort_report_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->string('pca_code')->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->decimal('allocation_percent', 7,4)->nullable();
            $table->decimal('additional_pay_fixed_monthly',9, 2 )->nullable();
            $table->string('type');
            $table->boolean('is_automatic')->default(0);
            $table->string('allocation_category')->nullable();
            $table->string('additional_pay_category')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('report_id')
                ->references('id')
                ->on('effort_reports')
                ->onDelete('cascade');

            $table->foreign('budget_id')
                ->references('id')
                ->on('budgets')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('effort_report_allocations');
    }
}
