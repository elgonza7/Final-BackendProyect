<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $postCategories = [
            ['post_id' => 1, 'category_id' => 1],
            ['post_id' => 2, 'category_id' => 2],
            ['post_id' => 3, 'category_id' => 5],
            ['post_id' => 3, 'category_id' => 1],
            ['post_id' => 4, 'category_id' => 4],
            ['post_id' => 5, 'category_id' => 1],
            ['post_id' => 5, 'category_id' => 5],
        ];

        foreach ($postCategories as $postCategory) {
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
