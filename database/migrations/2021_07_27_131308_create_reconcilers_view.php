<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconcilersView extends Migration
{
    public function up()
    {
        DB::statement(
            "CREATE OR REPLACE VIEW reconcilers_view AS
            SELECT DISTINCT c.person_id
            FROM contacts c
            INNER JOIN roles
            ON c.uwnetid = roles.uwnetid
            WHERE role = 'reconciler'
            UNION
            SELECT DISTINCT reconciler_person_id AS person_id
            FROM budgets WHERE reconciler_person_id IS NOT NULL;"
        );
    }

    public function down()
    {
        Schema::dropIfExists('reconcilers_view');
    }
}
