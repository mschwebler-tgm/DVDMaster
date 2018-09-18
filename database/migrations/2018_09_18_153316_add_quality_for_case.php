<?php

use App\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQualityForCase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE rentals CHANGE COLUMN quality dvd_quality ENUM('lost', 'bad', 'meh', 'okay', 'good')");
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('case_quality', ['lost', 'bad', 'meh', 'okay', 'good'])->nullable()->after('dvd_quality');
        });
        $rentals = Rental::whereNotNull('dvd_quality')->get();
        /** @var Rental $rental */
        foreach ($rentals as $rental) {
            $rental->update([
                'case_quality' => $rental->dvd_quality
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE rentals CHANGE COLUMN dvd_quality quality ENUM('lost', 'bad', 'meh', 'okay', 'good')");
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('case_quality');
        });
    }
}
