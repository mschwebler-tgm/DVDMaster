<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->boolean('belongs_to_collection')->nullable();
            $table->integer('budget')->nullable();
            $table->string('homepage')->nullable();
            $table->string('original_title')->nullable();
            $table->longText('overview')->nullable();
            $table->double('popularity')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('revenue')->nullable();
            $table->string('tagline')->nullable();
            $table->string('title');
            $table->double('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
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
        Schema::dropIfExists('movies');
    }
}
