<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('budgetno', 7)->unique();
            $table->unsignedBigInteger('pi_person_id')->nullable();
            $table->unsignedBigInteger('fiscal_person_id')->nullable();
            $table->unsignedBigInteger('reconciler_person_id')->nullable();
            $table->unsignedBigInteger('business_person_id')->nullable();
            $table->string('non_coe_name', 100)->nullable();
            $table->string('purpose_brief', 50)->nullable();
            $table->char('food', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
