<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->boolean('adult')->default(false);
            $table->string('also_known_as')->nullable();
            $table->longText('biography')->nullable();
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('homepage')->nullable();
            $table->string('name');
            $table->string('place_of_birth')->nullable();
            $table->double('popularity')->nullable();
            $table->string('profile_path')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('actors');
    }
}
