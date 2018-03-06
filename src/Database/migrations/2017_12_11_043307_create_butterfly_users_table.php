<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButterflyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('butterfly_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('lv');
            $table->string('name');
            $table->string('realName')->nullable();
            $table->string('thumb')->nullable();
            $table->string('email');
            $table->char('phone', 32)->nullable();
            $table->tinyInteger('verify')->unsigned();
            $table->integer('verifyTime')->unsigned()->nullable();
            $table->smallInteger('groupID');
            $table->string('password', 60);
            $table->string('api_token', 60)->unique();
            $table->rememberToken();
            $table->integer('deleted_at')->unsigned()->nullable();
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });
    }

    /**
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('butterfly_users');
    }
}
