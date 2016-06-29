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
            $table->text('activate_after')->nullable();
            $table->text('deactivate_after_times')->nullable();
            $table->text('deactivate_after_datetime')->nullable();
            $table->text('next_run_time')->nullable();
            $table->boolean('status');
            $table->integer('ran_times')->unsigned();
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
