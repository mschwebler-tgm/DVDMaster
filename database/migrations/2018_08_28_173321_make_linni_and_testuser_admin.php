<?php

use App\User;
use Illuminate\Database\Migrations\Migration;

class MakeLinniAndTestuserAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::whereIn('name', ['Linni', 'Testuser'])->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
