<?php

namespace Database\Seeders;

use App\Filament\Resources\Shield\RoleResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'access_log_viewer']);

        $roles = ["super_admin", "admin", "author"];

        foreach ($roles as $key => $role) {
            $roleCreated = (new (RoleResource::getModel()))->create(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            if ($role == 'super_admin') {
                $roleCreated->givePermissionTo('access_log_viewer');
            }
        }
    }
}
