<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
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
        ];

            foreach ($posts as $post) {
                \App\Models\Post::firstOrCreate(
                    ['title' => $post['title']], // Busca por este campo único
                    $post // Si no existe, crea con todos estos datos
                );
            }
            $this->command->info('Posts creados exitosamente!');
        
    }
}
