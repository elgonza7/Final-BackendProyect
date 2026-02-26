<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    // Crear posts de ejemplo para desarrollo
    public function run(): void
    {
        // Array de posts
        // IMPORTANTE: no incluyo category_id aquí porque la relación es many-to-many
        // Las categorías se asignan en PostCategorySeeder!
        $posts = [
            [
                'title' => 'Primer Post',
                'content' => 'Contenido del primer post',
                'user_id' => 1,
            ],
            [
                'title' => 'Segundo Post',
                'content' => 'Contenido del segundo post',
                'user_id' => 1,
            ],
            [
                'title' => 'Tercer Post',
                'content' => 'Contenido del tercer post',
                'user_id' => 2,
            ],
            [
                'title' => 'Cuarto Post',
                'content' => 'Contenido del cuarto post',
                'user_id' => 2,
            ],
            [
                'title' => 'Quinto Post',
                'content' => 'Contenido del quinto post',
                'user_id' => 3,
            ],

        ];


        foreach ($posts as $post) {
            // firstOrCreate evita duplicados si ejecuto el seeder múltiples veces
            \App\Models\Post::firstOrCreate(
                ['title' => $post['title']],
                $post
            );
        }
        $this->command->info('Posts creados exitosamente!');
        
    }
}
