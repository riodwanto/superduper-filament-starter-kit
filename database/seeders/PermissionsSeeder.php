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

        // Get all permissions
        $allPermissions = Permission::pluck('name')->toArray();

        // Get super admin role name from config
        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', $superAdminRoleName)->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions($allPermissions);
        }

        // Assign most permissions to admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            // Exclude only the most sensitive permissions if needed, otherwise assign all
            $admin->syncPermissions($allPermissions);
        }

        // Assign a sensible subset to editor
        $editor = Role::where('name', 'editor')->first();
        if ($editor) {
            $editorPermissions = array_filter($allPermissions, function ($perm) {
                // Editors can manage blog, banner, contact, and view users, but not delete users or roles
                return (
                    str_contains($perm, 'blog') ||
                    str_contains($perm, 'banner')
                );
            });
            $editor->syncPermissions($editorPermissions);
        }

        // Assign a minimal subset to author
        $author = Role::where('name', 'author')->first();
        if ($author) {
            $authorPermissions = array_filter($allPermissions, function ($perm) {
                // Authors can only create/update/view their own blog posts and view banner
                return (
                    str_starts_with($perm, 'view_blog::post') ||
                    str_starts_with($perm, 'create_blog::post') ||
                    str_starts_with($perm, 'update_blog::post') ||
                    str_starts_with($perm, 'view_banner::content') ||
                    str_starts_with($perm, 'view_banner::category')
                );
            });
            $author->syncPermissions($authorPermissions);
        }
    }
}
