<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRentalQualityStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE rentals MODIFY COLUMN quality ENUM('lost', 'bad', 'meh', 'okay', 'good')");
        Schema::table('rentals', function (Blueprint $table) {
            $table->boolean('like')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE rentals MODIFY COLUMN quality ENUM('lost', 'bad', 'good', 'original')");
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('like');
        });
    }
}
