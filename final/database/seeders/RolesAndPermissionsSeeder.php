<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos con guard 'web'
        $permissions = [
            // Posts
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            
            // Comentarios
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',
            
            // Usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Roles y permisos
            'manage roles',
            'manage permissions',
            
            // Categorías
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Admin
            'access admin panel',
            'view user activities',
            'view statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Refrescar la caché de permisos después de crearlos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles y asignar permisos

        // Rol: Usuario (permisos limitados)
        // Solo puede: ver posts, crear comentarios/mensajes, ver todos los posts
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'view posts',       // Ver posts
            'view comments',    // Ver comentarios
            'create comments',  // Enviar mensajes/comentarios
            'view categories',  // Ver categorías
        ]);

        // Rol: Editor (permisos intermedios)
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

        // Rol: Moderador (permisos avanzados)
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

        // Rol: Administrador (todos los permisos)
        // Puede: ver todos los perfiles, eliminar posts de otros, eliminar comentarios, todo lo del usuario
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles y permisos creados exitosamente!');
    }
}
