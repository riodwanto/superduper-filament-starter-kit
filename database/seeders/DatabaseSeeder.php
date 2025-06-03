<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UsersSeeder::class,
            BannersSeeder::class,
            BlogsSeeder::class,
            ContactUsSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
