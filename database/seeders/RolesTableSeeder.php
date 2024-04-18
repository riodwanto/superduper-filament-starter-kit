<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ["super_admin", "author", "admin_tenant"];
        $tenant = DB::table('tenants')->first();

        foreach ($roles as $key => $role) {
            DB::table('roles')->insert(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                    'tenant_id' => $role != "super_admin" ? $tenant->id : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
