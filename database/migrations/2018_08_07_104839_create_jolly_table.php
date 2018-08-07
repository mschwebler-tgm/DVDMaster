<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJollyTable extends Migration
{
    public function up()
    {
        Schema::create('jolly', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jolly');
    }
}
