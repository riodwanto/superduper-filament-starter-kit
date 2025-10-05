<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info('✅ Permission cache cleared');

        // Create custom permissions from config
        $customPermissions = config('filament-shield.custom_permissions', []);

        if (count($customPermissions) > 0) {
            $this->command->info('📝 Custom permissions:');

            foreach ($customPermissions as $key => $label) {
                $permission = Permission::firstOrCreate(
                    ['name' => $key],
                    ['guard_name' => 'web']
                );

                $this->command->line("   - {$key} ({$label})");
            }

            $this->command->info('✅ Created ' . count($customPermissions) . ' custom permission(s)');
        } else {
            $this->command->warn('⚠️  No custom permissions defined in config');
        }

        // Get all permissions
        $allPermissions = Permission::pluck('name')->toArray();

        $this->command->newLine();
        $this->command->info('📊 Total permissions: ' . count($allPermissions));

        // Assign permissions to roles
        $this->command->newLine();
        $this->command->info('🔐 Assigning permissions to roles...');
        $this->command->newLine();

        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');

        // Super Admin
        $superAdmin = Role::where('name', $superAdminRoleName)->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions($allPermissions);
            $this->command->info("   ✅ {$superAdminRoleName}: " . count($allPermissions) . ' permissions (ALL)');
        } else {
            $this->command->error("   ❌ {$superAdminRoleName} role not found!");
        }

        // Admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->syncPermissions($allPermissions);
            $this->command->info('   ✅ admin: ' . count($allPermissions) . ' permissions (ALL)');
        } else {
            $this->command->error('   ❌ admin role not found!');
        }

        // Editor permissions
        $editorPermissions = array_filter($allPermissions, function ($perm) {
            return str_contains($perm, 'blog')
                || str_contains($perm, 'banner');
        });

        $editor = Role::where('name', 'editor')->first();
        if ($editor) {
            $editor->syncPermissions($editorPermissions);
            $this->command->info('   ✅ editor: ' . count($editorPermissions) . ' permissions');
        } else {
            $this->command->error('   ❌ editor role not found!');
        }

        // Author permissions
        $authorPermissions = array_filter($allPermissions, function ($perm) {
            return str_starts_with($perm, 'view_blog::post')
                || str_starts_with($perm, 'create_blog::post')
                || str_starts_with($perm, 'update_blog::post')
                || str_starts_with($perm, 'view_banner::content')
                || str_starts_with($perm, 'view_banner::category');
        });

        $author = Role::where('name', 'author')->first();
        if ($author) {
            $author->syncPermissions($authorPermissions);
            $this->command->info('   ✅ author: ' . count($authorPermissions) . ' permissions');
        } else {
            $this->command->error('   ❌ author role not found!');
        }

        $this->command->newLine();
        $this->command->info('🎉 Permissions seeding completed!');
        $this->command->newLine();
    }
}
