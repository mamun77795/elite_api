<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_type');
            $table->string('code');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('rocket_number');
            $table->string('alternative_number');
            $table->string('division_id');
            $table->string('district_id');
            $table->string('thana_id');
            $table->string('password');
            $table->string('uid');
            $table->string('app_version');
            $table->string('platform');
            $table->string('device');
            $table->string('push_token');
            $table->string('imei');
            $table->string('user_token');
            $table->string('picture');
            $table->string('picture_type');
            $table->string('depo');
            $table->string('nid');
            $table->string('nid_picture');
            $table->string('nid_picture_type');
            $table->string('disable');
            $table->string('status');
            $table->string('register_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('dealer_users');
    }
}
