<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButterflyAdminLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('butterfly_admin_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('uid')->unsigned();
            $table->string('event');
            $table->text('origin')->nullable();
            $table->text('ending')->nullable();
            $table->string('ip');
            $table->string('iso_code');
            $table->string('city');
            $table->integer('created_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('butterfly_admin_log');
    }
}
