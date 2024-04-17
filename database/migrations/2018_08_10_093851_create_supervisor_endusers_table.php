<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorEndusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisor_endusers', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('supervisor_id')->unsigned();
          $table->foreign('supervisor_id')->references('id')->on('supervisors');
          $table->integer('enduser_id')->unsigned();
          $table->foreign('enduser_id')->references('id')->on('endusers');
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
        Schema::dropIfExists('supervisor_endusers');
    }
}
