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
        DB::statement("ALTER TABLE rentals MODIFY COLUMN quality ENUM('lost', 'bad', 'meh', 'okay', 'good', 'original')");
        Schema::table('rentals', function (Blueprint $table) {
            $table->boolean('like')->nullable();
        });

        \App\Rental::where('quality', 'good')->update(['quality' => 'okay']);
        \App\Rental::where('quality', 'original')->update(['quality' => 'good']);

        DB::statement("ALTER TABLE rentals MODIFY COLUMN quality ENUM('lost', 'bad', 'meh', 'okay', 'good')");
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
