<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToMovies extends Migration
{
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
