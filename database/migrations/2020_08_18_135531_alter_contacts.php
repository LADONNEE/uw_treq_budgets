<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterContacts extends Migration
{
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->boolean('is_faculty')->default(0)->after('email');
            $table->boolean('is_80_20')->default(0)->after('is_faculty');
            $table->unsignedBigInteger('default_budget_id')->nullable()->after('is_faculty');
            $table->unsignedBigInteger('fiscal_contact_id')->nullable()->after('default_budget_id');
            $table->date('end_at')->nullable()->after('created_at');
            $table->unique('person_id');
            $table->unique('uwnetid');
            $table->unique('studentno');
            $table->unique('employeeid');

            $table->foreign('default_budget_id')
                ->references('id')
                ->on('budgets')
                ->onDelete('cascade');

            $table->foreign('fiscal_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        //
    }
}
