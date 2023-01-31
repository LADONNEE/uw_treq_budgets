<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code', 10)->nullable();
            $table->string('description');
            $table->string('allocation_type_frequency');
            $table->string('purpose');
            $table->string('pre_approval_required');
            $table->string('action_item');
            $table->string('workday_code',24)->nullable();
            $table->string('workday_description')->nullable();

            $table->unsignedBigInteger('authorizer_person_id')->nullable();
            $table->unsignedBigInteger('fiscal_person_id')->nullable();
            
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
        Schema::dropIfExists('project_codes');
    }
}
