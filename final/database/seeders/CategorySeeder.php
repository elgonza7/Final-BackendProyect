<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tecnología', 'slug' => 'tecnologia'],
            ['name' => 'Salud', 'slug' => 'salud'],
            ['name' => 'Deportes', 'slug' => 'deportes'],
            ['name' => 'Entretenimiento', 'slug' => 'entretenimiento'],
            ['name' => 'Ciencia', 'slug' => 'ciencia'],
            ['name' => 'otros', 'slug' => 'otros']
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
