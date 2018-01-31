<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButterflyAdminGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('butterfly_admin_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('lv');
            $table->string('color')->nullable();
            $table->text('permissions')->nullable();
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
        Schema::dropIfExists('butterfly_admin_group');
    }
}
