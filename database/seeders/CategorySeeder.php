<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Categorías de ejemplo para el catálogo BLÜK.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Camisetas', 'description' => 'Camisetas de algodón y diseño urbano'],
            ['name' => 'Accesorios', 'description' => 'Gorras y complementos de estilo urbano'],
            ['name' => 'Bolsos', 'description' => 'Bolsos exclusivos con diseños originales'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
