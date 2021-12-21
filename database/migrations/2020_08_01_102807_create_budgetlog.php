<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgetlog', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->dateTime('created_at');
            $table->char('uwnetid', 8);
            $table->string('eventtype', 30);
            $table->bigInteger('budget_id')->unsigned()->nullable();
            $table->bigInteger('person_id')->unsigned()->nullable();
            $table->text('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgetlog');
    }
}
