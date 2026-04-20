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
            ['name' => 'Pantalones', 'description' => 'Pantalones casual y streetwear'],
            ['name' => 'Zapatillas', 'description' => 'Calzado deportivo y urbano'],
            ['name' => 'Accesorios', 'description' => 'Gorras, mochilas, cinturones y más'],
            ['name' => 'Sudaderas', 'description' => 'Sudaderas con y sin capucha'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
