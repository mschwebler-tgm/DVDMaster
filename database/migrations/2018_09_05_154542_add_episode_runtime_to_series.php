<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEpisodeRuntimeToSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->smallInteger('episode_runtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('episode_runtime');
        });
    }
}
