<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([RolesAndPermissionsSeeder::class]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

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

        User::factory(10)->create();
        
        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
            PostCategorySeeder::class,
            CommentSeeder::class,
        ]);
        $this->command->info('¡Sembrado completado!');
    }
}
