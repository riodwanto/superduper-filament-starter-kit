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

        Permission::create(['name' => 'access_log_viewer']);

        $blogPermissions = [
            'publish_blog::post',           // Can publish posts
            'archive_blog::post',           // Can archive posts
            'feature_blog::post',           // Can mark posts as featured
            'update_any_blog::post',        // Can edit any post (vs own posts only)
            'change_author_blog::post',     // Can change post author
            'approve_blog::post',           // Can approve pending posts
            'schedule_blog::post',          // Can schedule posts for future publication
            'manage_blog::seo',             // Can edit SEO meta fields
            'bulk_publish_blog::post',      // Can bulk publish posts
            'view_analytics_blog::post',    // Can view post analytics (views, etc.)
        ];

        foreach ($blogPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = ["super_admin", "admin", "editor", "author"];

        foreach ($roles as $key => $role) {
            $roleCreated = (new (RoleResource::getModel()))->firstOrCreate(
                ['name' => $role, 'guard_name' => 'web'],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Assign permissions based on role
            if ($role == 'super_admin') {
                $roleCreated->givePermissionTo('access_log_viewer');
                // Super admin gets all permissions (handled by Shield)
            }
            elseif ($role == 'admin') {
                // Admin gets all blog permissions
                $roleCreated->givePermissionTo($blogPermissions);
                $roleCreated->givePermissionTo('access_log_viewer');
            }
            elseif ($role == 'editor') {
                // Editor can publish, feature, approve, manage SEO, bulk operations
                $editorPermissions = [
                    'publish_blog::post',
                    'archive_blog::post',
                    'feature_blog::post',
                    'update_any_blog::post',
                    'approve_blog::post',
                    'schedule_blog::post',
                    'manage_blog::seo',
                    'bulk_publish_blog::post',
                    'view_analytics_blog::post',
                ];
                $roleCreated->givePermissionTo($editorPermissions);
            }
            elseif ($role == 'author') {
                // Author has limited permissions - only own posts, basic operations
                $authorPermissions = [
                    'view_blog::post',          // Can view own posts
                    'schedule_blog::post',      // Can schedule own posts
                    'view_analytics_blog::post', // Can view own post analytics
                ];
                $roleCreated->givePermissionTo($authorPermissions);
                // Note: Authors get create/update permissions for own posts via policy logic
            }
        }
    }
}
