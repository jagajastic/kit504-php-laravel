<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a director.
        User::factory(1)
            ->director()
            ->state(function (array $attributes) {
                return [
                    'email' => 'director@utas.org',
                ];
            })
            ->create();

        // Create students.
        User::factory(5)->student()->create();

        // Create employees.
        User::factory(5)->employee()->create();
    }
}
