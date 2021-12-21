<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovals extends Migration
{
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->string('type');
            $table->tinyInteger('sequence');
            $table->unsignedBigInteger('assigned_to_contact_id');
            $table->datetime('notified_at')->nullable();
            $table->unsignedBigInteger('completed_by_contact_id')->nullable();
            $table->string('response');
            $table->text('message')->nullable();
            $table->date('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('report_id')
                ->references('id')
                ->on('effort_reports')
                ->onDelete('cascade');

            $table->foreign('assigned_to_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->foreign('completed_by_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
