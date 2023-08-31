<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     protected $initialUsers = [
        'nbedani' => 'budget:super'
    ];

    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->char('uwnetid', 8); //->charset('utf8')->collation('utf8_unicode_ci');
            $table->string('role', 40);
            $table->timestamps();
        });
        $this->fill();
    }

    public function fill()
    {
        foreach ($this->initialUsers as $uwnetid => $role) {
            \App\Models\Role::firstOrCreate([
                'uwnetid' => $uwnetid,
                'role' => $role,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
