<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButterflyAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('butterfly_admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parentID')->unsigned();
            $table->string('routeName');
            $table->integer('listOrder')->unsigned()->default(0);
            $table->tinyInteger('display')->unsigned()->default(1);
            $table->string('icon')->default('');
            $table->integer('deleted_at')->unsigned()->nullable();
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('butterfly_admin_menu');
    }
}
