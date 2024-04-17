<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarCodeDuplicatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_code_duplicates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('painter_id');
            $table->string('bar_code');
            $table->string('point');
            $table->string('identifier');
            $table->string('no_of_duplicates');
            $table->string('no_of_used');
            $table->timestamp('created_at')->useCurrent();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bar_code_duplicates');
    }
}
