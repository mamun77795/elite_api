<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumeTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volume_tranfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('painter_id');
            $table->string('product_id');
            $table->string('quantity');
            $table->string('code');
            $table->string('code2');
            $table->string('dealer_point');
            $table->string('painter_point');
            $table->string('status');
            $table->string('accepted_by');
            $table->string('soft_delete');
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
        Schema::dropIfExists('volume_tranfers');
    }
}
