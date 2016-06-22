<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hub_id')->unsigned();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('cascade');
            $table->string('name',100);
            $table->text('trigger_type');
            $table->integer('trigger_bot')->unsigned()->nullable();
            $table->foreign('trigger_bot')->references('id')->on('bots')->onDelete('cascade');
            $table->boolean('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
