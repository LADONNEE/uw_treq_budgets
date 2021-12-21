<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusLookup extends Migration
{
    public $data = [
        [ 1, 'Open', 'Open' ],
        [ 2, 'Principal', 'Principal account - no expenses allowed' ],
        [ 3, 'Closing', 'Closing - open for only expense transfers' ],
        [ 4, 'Closed', 'Closed - delete after biennium' ],
    ];

    public function up()
    {
        Schema::create('status_lookup', function (Blueprint $table) {
            $table->unsignedTinyInteger('uw_code')->primary();
            $table->string('short', 50);
            $table->string('full', 100);
            $table->timestamps();
        });

        $this->fill();
    }

    private function fill()
    {
        foreach ($this->data as $item) {
            $status = \App\Models\StatusLookup::firstOrNew(['uw_code' => $item[0]]);
            $status->fill([
                'short' => $item[1],
                'full' => $item[2],
            ]);
            $status->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_lookup');
    }
}
