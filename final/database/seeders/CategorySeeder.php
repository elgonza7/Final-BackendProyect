<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    // Llenar tabla categories con datos iniciales
    public function run(): void
    {
        // Array de categorías para el blog
        // Nota: el slug se usa para URLs amigables (ej: /categoria/tecnologia)
        $categories = [
            ['name' => 'Tecnología', 'slug' => 'tecnologia'],
            ['name' => 'Salud', 'slug' => 'salud'],
            ['name' => 'Deportes', 'slug' => 'deportes'],
            ['name' => 'Entretenimiento', 'slug' => 'entretenimiento'],
            ['name' => 'Ciencia', 'slug' => 'ciencia'],
            ['name' => 'otros', 'slug' => 'otros']
        ];

        foreach ($categories as $category) {
            // firstOrCreate: busca por 'name', si no existe lo crea completo
            // Útil para evitar duplicados al ejecutar seed varias veces
            \App\Models\Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
