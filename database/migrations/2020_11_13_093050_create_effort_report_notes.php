<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEffortReportNotes extends Migration
{
    public function up()
    {
        Schema::create('effort_report_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->string('section', 50)->default('snapshot');
            $table->text('note');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('report_id')
                ->references('id')->on('effort_reports')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('effort_report_notes');
    }
}
