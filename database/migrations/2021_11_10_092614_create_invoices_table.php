<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('date');
            $table->string('invoice');
            $table->string('product_code');
            $table->string('product_name');
            $table->string('pack_size');
            $table->string('shade_name');
            $table->string('point');
            $table->string('quantity');
            $table->string('net_amount');
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
        Schema::dropIfExists('invoices');
    }
}
