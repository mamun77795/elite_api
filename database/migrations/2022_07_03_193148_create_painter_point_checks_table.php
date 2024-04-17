<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePainterPointChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painter_point_checks', function (Blueprint $table) {
          $table->increments('id');
          $table->string('painter_id');
          $table->string('point');
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
        Schema::dropIfExists('painter_point_checks');
    }
}
