<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE OR REPLACE VIEW managers_view AS
            SELECT DISTINCT c.person_id
            FROM contacts c
            INNER JOIN roles
            ON c.uwnetid = roles.uwnetid
            WHERE role = 'manager'
            UNION
            SELECT DISTINCT fiscal_person_id AS person_id
            FROM budgets WHERE fiscal_person_id IS NOT NULL;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers_view');
    }
}
