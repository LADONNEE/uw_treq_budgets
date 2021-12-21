<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEffortReports extends Migration
{
    public function up()
    {
        Schema::create('effort_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_contact_id');
            $table->string('stage')->default('PENDING');
            $table->string('type');
            $table->unsignedBigInteger('creator_contact_id');
            $table->text('description')->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->datetime('notified_at')->nullable();
            $table->timestamps();

            $table->foreign('faculty_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->foreign('creator_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('effort_reports');
    }
}
