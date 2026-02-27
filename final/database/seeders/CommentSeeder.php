<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
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
            [
                'name' => 'Comentario 3',
                'content' => 'Contenido del comentario 3',
                'post_id' => 2,
                'user_id' => 2,
            ],
            [
                'name' => 'Comentario 4',
                'content' => 'Contenido del comentario 4',
                'post_id' => 2,
                'user_id' => 2,
            ],
            [
                'name' => 'Comentario 5',
                'content' => 'Contenido del comentario 5',
                'post_id' => 3,
                'user_id' => 1,
            ],
        ];

        foreach ($comments as $comment) {
            \App\Models\Comment::firstOrCreate(
                ['name' => $comment['name']],
                $comment
            );
        }
    }
}
