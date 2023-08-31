<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('worktags_budgets_fdm_translator', function (Blueprint $table) {
            $table->id();
            $table->string('tag_type', 50);
            $table->string('workday_code', 100);
            $table->string('budgetno', 10);
            $table->string('system', 50)->nullable();
            $table->string('workday_name', 255)->nullable();
            $table->string('mapping_status', 50)->nullable();
            $table->string('orgcode', 10)->nullable();
            $table->dateTime('loaded_at');
            $table->timestamps();

            $table->index(['tag_type', 'workday_code']);
            $table->index('budgetno');
        });
    }

    public function down()
    {
        Schema::dropIfExists('worktags_budgets_fdm_translator');
    }
};
