<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Class AddWorktagsAddCcWorktagId extends Migration
{
    public function up()
    {
        Schema::table('worktags', function (Blueprint $table) {
            $table->unsignedBigInteger('cc_worktag_id')->nullable()->after('hierarchy_id');

            $table->foreign('cc_worktag_id')
                ->references('id')
                ->on('worktags')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('worktags', 'cc_worktag_id')) {
            Schema::table('worktags', function (Blueprint $table) {
                $table->dropColumn('cc_worktag_id');
            });
        }
    }
};
