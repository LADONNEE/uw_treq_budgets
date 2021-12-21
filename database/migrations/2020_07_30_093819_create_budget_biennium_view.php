<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetBienniumView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'CREATE OR REPLACE VIEW budget_biennium_view AS
                    SELECT w.id AS uw_budget_id
                    , b.id AS budget_id
                    , b.id
                    , b.budgetno
                    , w.BienniumYear AS biennium
                    , w.BudgetName AS name
                    , w.BudgetNbr
                    , w.BudgetStatus
                    , w.OrgCode
                    , w.PrincipalInvestigator
                    , b.pi_person_id
                    , b.fiscal_person_id
                    , b.reconciler_person_id
                    , b.business_person_id
                    , b.purpose_brief
                    , b.food
                    FROM uw_budgets_cache w
                    INNER JOIN budgets b
                      ON b.budgetno = w.budgetno'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS budget_biennium_view');
    }
}
