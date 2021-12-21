<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBudgets extends Migration
{
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->tinyInteger('is_coe')->default(1)->after('budgetno');
        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('is_coe');
        });
    }
}
