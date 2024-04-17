<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisors', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->foreign('user_id')->references('id')->on('users');
          $table->integer('client_id')->unsigned();
          $table->foreign('client_id')->references('id')->on('clients');
          $table->integer('role_supervisor_id')->unsigned();
          $table->foreign('role_supervisor_id')->references('id')->on('role_supervisors');
          $table->string('code')->unique();
          $table->string('phone_number');
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
        Schema::dropIfExists('supervisors');
    }
}
