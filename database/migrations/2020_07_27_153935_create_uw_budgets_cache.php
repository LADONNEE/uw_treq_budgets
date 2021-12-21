<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUwBudgetsCache extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uw_budgets_cache', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('BienniumYear', 4);
            $table->char('BudgetNbr', 6);
            $table->string('BudgetName', 50)->nullable();
            $table->unsignedTinyInteger('BudgetStatus')->nullable();
            $table->char('BudgetType', 2)->nullable();
            $table->char('BudgetClass', 2)->nullable();
            $table->string('BudgetClassDesc', 50)->nullable();
            $table->date('EffectiveDate')->nullable();
            $table->string('OrgCode', 10)->nullable();
            $table->char('PayrollUnitCode', 4)->nullable();
            $table->integer('PrincipalInvestigatorId')->nullable();
            $table->string('PrincipalInvestigator', 100)->nullable();
            $table->date('TotalPeriodBeginDate')->nullable();
            $table->date('TotalPeriodEndDate')->nullable();
            $table->decimal('AccountingIndirectCostRate', 7, 4)->nullable();
            $table->string('budgetno', 7)->nullable();
            $table->boolean('updating')->default(0);
            $table->timestamps();

            $table->index('BienniumYear');
            $table->index('budgetno');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uw_budgets_cache');
    }
}
