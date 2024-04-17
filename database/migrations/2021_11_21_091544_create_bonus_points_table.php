<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('painter_id');
            $table->string('token_number');
            $table->string('token_product');
            $table->string('token_product_size');
            $table->string('actual_product');
            $table->string('actual_product_size');
            $table->string('scan_point');
            $table->string('project_name');
            $table->string('project_address');
            $table->string('project_volume');
            $table->string('member_type');
            $table->string('type');
            $table->string('remarks');
            $table->string('bonus_point');
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
        Schema::dropIfExists('bonus_points');
    }
}
