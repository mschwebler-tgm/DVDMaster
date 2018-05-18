<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserSeeder::class);
         \Illuminate\Support\Facades\Artisan::call('import:genres');
         \Illuminate\Support\Facades\Artisan::call('import:movies');
         \Illuminate\Support\Facades\Artisan::call('import:actors');
    }
}
