<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'firstName' => env('APP_ADMIN_FIRSTNAME'),
            'lastName' => env('APP_ADMIN_LASTNAME'),
            'email' => env('APP_ADMIN_EMAIL'),
            'role' => 'admin',
            'password' => env('APP_ADMIN_PASSWORD')
        ]);


        // If you want fake data, you can uncomment this following lines
        // $this->call([
        //     UserSeeder::class,
        //     ClientSeeder::class,
        //     BrandsSeeder::class,
        //     TypesSeeder::class,
        //     DevicesSeeder::class,
        //     TicketsSeeder::class,
        // ]);
    }
}
