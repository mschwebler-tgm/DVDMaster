<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesToRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE rentals MODIFY movie_id INTEGER UNSIGNED');
        Schema::table('rentals', function (Blueprint $table) {
            $table->unsignedInteger('series_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE rentals MODIFY movie_id INTEGER UNSIGNED NOT NULL');
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('series_id');
        });
    }
}
