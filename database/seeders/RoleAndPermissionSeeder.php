<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        /**
         * Create Roles
         */
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $contributorRole = Role::firstOrCreate(['name' => 'contributor', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        /**
         * Create Permissions
         */
        $permissions = [
            // Post permissions
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view posts',

            // Book permissions
            'create books',
            'edit books',
            'delete books',
            'view books',

            // Service permissions
            'create services',
            'edit services',
            'delete services',
            'view services',

            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Settings
            'edit settings',
            'view settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        /**
         * Assign Permissions to Roles
         */

        // Admin: All permissions
        $adminRole->syncPermissions(Permission::all());

        // Contributor: Limited permissions (posts, books, services - view/create/edit own, cannot delete/publish)
        $contributorPermissions = [
            'view posts',
            'create posts',
            'edit posts',
            'view books',
            'view services',
        ];
        $contributorRole->syncPermissions($contributorPermissions);

        // User: Minimal permissions (viewing only)
        $userRole->syncPermissions(['view posts', 'view books', 'view services']);

        /**
         * Create test accounts if they don't exist
         */

        // Check if there's already an admin (don't overwrite existing)
        if (!User::where('role', 'admin')->exists()) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@test.com'],
                [
                    'name' => 'Administrator',
                    'password' => Hash::make('admin'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
            $admin->assignRole('admin');
        }

        // Create a sample contributor account
        if (!User::where('role', 'contributor')->where('email', 'contributor@test.com')->exists()) {
            $contributor = User::firstOrCreate(
                ['email' => 'contributor@test.com'],
                [
                    'name' => 'Kontributor Binakarya Cendekia',
                    'password' => Hash::make('contributor'),
                    'role' => 'contributor',
                    'email_verified_at' => now(),
                ]
            );
            $contributor->assignRole('contributor');
        }

        echo "\n✅ Roles and permissions seeded successfully!\n\n";
        echo "Test Accounts Created:\n";
        echo "Admin: admin@test.com / admin\n";
        echo "Contributor: contributor@test.com / contributor\n\n";
    }
}
