<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Class WorktagHierarchyTable extends Migration
{
    public function up()
    {
        Schema::create('worktag_hierarchy', function (Blueprint $table) {
            $table->id();
            $table->string('tag_type', 50);
            $table->string('workday_code', 100)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('parent_workday_code', 100)->nullable();
            $table->timestamps();

            $table->index(['tag_type', 'workday_code']);

            $table->foreign('parent_id')
                ->references('id')
                ->on('worktag_hierarchy')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('worktag_hierarchy');
    }
};
