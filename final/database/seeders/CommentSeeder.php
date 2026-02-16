<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $comments = [
            [
                'name' => 'Comentario 1',
                'content' => 'Contenido del comentario 1',
                'post_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Comentario 2',
                'content' => 'Contenido del comentario 2',
                'post_id' => 1,
                'user_id' => 1,
            ],
        ];

        foreach ($comments as $comment) {
            \App\Models\Comment::firstOrCreate(
                ['name' => $comment['name']], // Busca por este campo único
                $comment // Si no existe, crea con todos estos datos
            );
        }
    }
}
