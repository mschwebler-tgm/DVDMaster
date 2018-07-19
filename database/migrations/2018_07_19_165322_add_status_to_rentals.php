<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToRentals extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('state', ['pending', 'complete', 'lost', 'complete_bad_shape'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
}
