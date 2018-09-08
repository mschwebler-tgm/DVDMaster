<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tmdb_id')->nullable();;
            $table->unsignedInteger('series_id');
            $table->string('name');
            $table->longText('overview')->nullable();
            $table->unsignedInteger('season_number');
            $table->string('poster_path')->nullable();
            $table->date('air_date')->nullable();

            $table->foreign('series_id')->references('id')->on('series');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}
