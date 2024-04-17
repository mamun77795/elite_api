<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('year');
            $table->string('age');
            $table->string('product_id');
            $table->string('quantity');
            $table->string('pack_size');
            $table->string('total_volume');
            $table->string('actual_balance');
            $table->string('year_end_balance');
            $table->string('opening_balance');
            $table->string('transferable_stock');
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
        Schema::dropIfExists('year_stocks');
    }
}
