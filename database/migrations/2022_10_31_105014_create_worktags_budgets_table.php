<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Class CreateWorktagsBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('worktags_budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('worktag_id');
            $table->string('edited_by', 20)->nullable();
            $table->timestamps();

            $table->foreign('budget_id')
                ->references('id')
                ->on('budgets')
                ->onDelete('cascade');

            $table->foreign('worktag_id')
                ->references('id')
                ->on('worktags')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('worktags_budgets');
    }
};
