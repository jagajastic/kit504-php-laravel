<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a shop.
        $shop = Shop::factory(1)->create()->first();

        // Create manager for shop.
        User::factory(1)
            ->for($shop)
            ->manager()
            ->create();

        // Create shop staffs.
        User::factory(5)
            ->for($shop)
            ->staff()
            ->create();
    }
}
