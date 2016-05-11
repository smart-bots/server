<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighpermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highpermissions', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('member_id')->unsigned();
            // $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('hub_id')->unsigned();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('cascade');
            $table->integer('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('highpermissions');
    }
}
