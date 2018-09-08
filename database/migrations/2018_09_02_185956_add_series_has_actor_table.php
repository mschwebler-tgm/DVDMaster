<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesHasActorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_has_actor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('series_id');
            $table->unsignedInteger('actor_id');

            $table->foreign('series_id')->references('id')->on('series');
            $table->foreign('actor_id')->references('id')->on('actors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_has_actor');
    }
}
