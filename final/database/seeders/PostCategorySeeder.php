<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategorySeeder extends Seeder
{
    // NOTA: Este seeder llena la tabla pivote post_category
    // Recordar: relación muchos a muchos = necesita tabla intermedia!
    public function run(): void
    {
        // Array con las relaciones post-categoría
        // Importante: un post puede tener VARIAS categorías (ej: post 3 y 5)
        $postCategories = [
            ['post_id' => 1, 'category_id' => 1], // Post 1 -> Tecnología
            ['post_id' => 2, 'category_id' => 2], // Post 2 -> Salud
            
            // Post 3 tiene 2 categorías (esto es válido en many-to-many)
            ['post_id' => 3, 'category_id' => 5], // -> Ciencia
            ['post_id' => 3, 'category_id' => 1], // -> Tecnología también
            
            ['post_id' => 4, 'category_id' => 4], // Post 4 -> Entretenimiento
            
            // Post 5 también con múltiples
            ['post_id' => 5, 'category_id' => 1], // -> Tecnología
            ['post_id' => 5, 'category_id' => 5], // -> Ciencia
        ];

        foreach ($postCategories as $postCategory) {
            // Verificar duplicados antes de insertar (por si ejecutamos el seeder varias veces)
            $exists = DB::table('post_category')
                ->where('post_id', $postCategory['post_id'])
                ->where('category_id', $postCategory['category_id'])
                ->exists();

            if (!$exists) {
                DB::table('post_category')->insert([
                    'post_id' => $postCategory['post_id'],
                    'category_id' => $postCategory['category_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Relaciones Post-Category creadas exitosamente!');
    }
}
