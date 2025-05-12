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
            RolesAndPermissionsSeeder::class,
            UsersTableSeeder::class,
            BannersTableSeeder::class,
            BlogsTableSeeder::class,
            ContactUsTableSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
