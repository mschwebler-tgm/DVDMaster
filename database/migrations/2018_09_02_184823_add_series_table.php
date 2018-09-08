<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('original_name')->nullable();
            $table->unsignedInteger('tmdb_id')->nullable();
            $table->longText('overview')->nullable();
            $table->string('type')->nullable();
            $table->integer('number_of_seasons')->nullable();
            $table->integer('number_of_episodes')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('based_on_book')->nullable();
            $table->boolean('true_story')->nullable();
            $table->boolean('blue_ray')->nullable();
            $table->string('homepage')->nullable();
            $table->double('popularity')->nullable();
            $table->date('first_air_date')->nullable();
            $table->double('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('poster_path')->nullable();
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
        Schema::dropIfExists('series');
    }
}
