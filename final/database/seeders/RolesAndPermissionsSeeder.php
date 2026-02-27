<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage roles',
            'manage permissions',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'access admin panel',
            'view user activities',
            'view statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'view posts',
            'view comments',
            'create comments',
            'view categories',
        ]);

        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            'view posts',
            'create posts',
            'edit posts',
            'publish posts',
            'view comments',
            'create comments',
            'edit comments',
            'view categories',
            'create categories',
            'edit categories',
        ]);

        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $moderatorRole->givePermissionTo([
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',
            'view users',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view user activities',
        ]);

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles y permisos creados exitosamente!');
    }
}
