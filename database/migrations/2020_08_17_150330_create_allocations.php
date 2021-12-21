<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAllocations extends Migration
{
    public function up()
    {
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_contact_id');
            $table->unsignedBigInteger('budget_id');
            $table->string('pca_code')->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->decimal('allocation_percent', 7,4)->nullable();
            $table->decimal('additional_pay_fixed_monthly',9, 2 )->nullable();
            $table->string('type');
            $table->string('allocation_category')->nullable();
            $table->string('additional_pay_category')->nullable();
            $table->text('note')->nullable();
            $table->unsignedMediumInteger('edited_by');
            $table->timestamps();

            $table->foreign('faculty_contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->foreign('budget_id')
                ->references('id')
                ->on('budgets')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('allocations');
    }
}
