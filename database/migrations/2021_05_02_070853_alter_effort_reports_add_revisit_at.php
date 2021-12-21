<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEffortReportsAddRevisitAt extends Migration
{
    public function up()
    {
        Schema::table('effort_reports', function (Blueprint $table) {
            $table->date('revisit_at')->nullable()->after('end_at');
            $table->date('revisit_notified_at')->nullable()->after('revisit_at');
        });
    }

    public function down()
    {
        Schema::table('effort_reports', function (Blueprint $table) {
            $table->dropColumn('revisit_at');
            $table->dropColumn('revisit_notified_at');
        });
    }
}
