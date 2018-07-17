<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomColsToMovies extends Migration
{
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dateTime('last_seen')->nullable();
            $table->unsignedInteger('sorted_after')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('based_on_book')->nullable();
            $table->boolean('true_story')->nullable();
            $table->boolean('blue_ray')->nullable();

            $table->foreign('sorted_after')->references('id')->on('movies');
        });
    }

    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign('sorted_after');
            $table->dropColumn(
                [
                    'last_seen',
                    'duration',
                    'comment',
                    'based_on_book',
                    'true_story',
                    'blue_ray'
                ]
            );
        });
    }
}
