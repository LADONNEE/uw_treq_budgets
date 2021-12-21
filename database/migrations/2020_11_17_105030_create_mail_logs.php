<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailLogs extends Migration
{
    public function up()
    {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('effort_report_id')->nullable();
            $table->string('mailable', 300);
            $table->string('email', 300);
            $table->dateTime('attempted_at');
            $table->string('result', 50);
            $table->longText('metadata')->nullable();
            $table->longText('error')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_logs');
    }
}
