<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hub_id')->unsigned();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('cascade');
            $table->string('name',100);
            $table->text('description');
            $table->text('action');
            $table->tinyInteger('trigger_type');
            $table->integer('trigger_id')->unsigned(); // event_id or bot_id
            $table->tinyInteger('condition_type');
            $table->tinyInteger('condition_method');
            $table->text('condition_data');
            $table->text('activate_after');
            $table->text('deactivate_after_times');
            $table->text('deactivate_after_datetime');
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
        Schema::drop('automations');
    }
}
