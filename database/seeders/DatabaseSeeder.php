<?php

namespace Database\Seeders;

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
        if (\config('app.env') !== 'production') {
            $this->call(UserSeeder::class);
            $this->call(ShopSeeder::class);
        }
    }
}
