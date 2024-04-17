<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedeemPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('volumes');
            $table->string('total_scan_point');
            $table->string('bonus_point');
            $table->string('redeem_point');
            $table->string('total_point');
            $table->string('dealer_id');
            $table->string('painter_id');
            $table->string('transaction_code');
            $table->string('status');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('transection_date');
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
        Schema::dropIfExists('redeem_points');
    }
}
