<?php

namespace Database\Seeders;

use App\Filament\Resources\Shield\RoleResource;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [config('filament-shield.super_admin.name', 'super_admin'), "admin", "editor", "author"];

        foreach ($roles as $role) {
            (new (RoleResource::getModel()))->firstOrCreate(
                ['name' => $role, 'guard_name' => 'web'],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
