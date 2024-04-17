<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarCodeDuplicateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_code_duplicate_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token_identifier');
            $table->string('token_no');
            $table->string('product_name_code');
            $table->string('no_of_duplicates');
            $table->string('token_amount');
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
        Schema::dropIfExists('bar_code_duplicate_infos');
    }
}
