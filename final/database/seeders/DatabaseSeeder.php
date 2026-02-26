<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // SEEDER PRINCIPAL: aquí llamo a todos los demás seeders en orden
    // ORDEN IMPORTANTE: primero roles, luego users, luego el resto (por las FK)
    public function run(): void
    {
        // Paso 1: Roles y permisos (usando Spatie)
        $this->call([RolesAndPermissionsSeeder::class]);

        // Paso 2: Crear usuarios de prueba con roles específicos
        // firstOrCreate = busca por email, si no existe lo crea (evita duplicados)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'), // TODO: cambiar en producción!
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin'); // Spatie: asignar rol

        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $testUser->assignRole('user');

        $commonUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Common User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $commonUser->assignRole('user');

        // Factory: crear 10 usuarios random con Faker
        User::factory(10)->create();
        
        // Paso 3: Datos del blog (orden: categories -> posts -> relación -> comments)
        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
            PostCategorySeeder::class, // tabla pivote!
            CommentSeeder::class,
        ]);
        $this->command->info('¡Sembrado completado!');
    }
}
