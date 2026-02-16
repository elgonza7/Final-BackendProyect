<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
            ],
            [
                'name' => 'Salud',
                'slug' => 'salud',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['name' => $category['name']], // Busca por este campo único
                $category // Si no existe, crea con todos estos datos
            );
        }
    }
}
