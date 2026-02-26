<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    // SPATIE PERMISSIONS: sistema de roles y permisos
    // Documentación: https://spatie.be/docs/laravel-permission
    public function run(): void
    {
        // Limpiar caché de Spatie (importante al crear/modificar permisos)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir todos los permisos del sistema
        // Formato: 'verbo recurso' (ej: 'create posts')
        $permissions = [
            // CRUD Posts
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            
            // CRUD Comentarios
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',
            
            // CRUD Usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Gestión de roles
            'manage roles',
            'manage permissions',
            
            // CRUD Categorías
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Panel admin
            'access admin panel',
            'view user activities',
            'view statistics',
        ];

        // Crear cada permiso en la BD
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Limpiar caché nuevamente después de crear
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === CREAR ROLES Y ASIGNAR PERMISOS ===
        
        // ROL USER: usuario básico registrado
        // Solo puede ver y comentar, no crear/editar posts
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'view posts',
            'view comments',
            'create comments',  // puede comentar
            'view categories',
        ]);

        // ROL EDITOR: puede crear y editar posts
        // No puede eliminar ni gestionar usuarios
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

        // ROL MODERATOR: puede eliminar posts/comments ofensivos
        // Puede ver actividad de usuarios pero no eliminarlos
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $moderatorRole->givePermissionTo([
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',      // puede moderar contenido
            'publish posts',
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',   // puede eliminar comentarios ofensivos
            'view users',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view user activities',
        ]);

        // ROL ADMIN: control total del sistema
        // Permission::all() = TODOS los permisos
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles y permisos creados exitosamente!');
    }
}
