<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('member_id')->unsigned();
            // $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('hub_id')->unsigned();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('cascade');
            $table->string('name',100);
            $table->text('description');
            $table->text('action');
            $table->tinyInteger('type');
            $table->text('data');
            $table->tinyInteger('condition_type');
            $table->tinyInteger('condition_method');
            $table->text('condition_data');
            $table->text('activate_after');
            $table->text('deactivate_after_times');
            $table->text('deactivate_after_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schedules');
    }
}
