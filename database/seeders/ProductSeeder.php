<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Productos de prueba distribuidos por categoría.
     */
    public function run(): void
    {
        $products = [
            // Camisetas
            [
                'category' => 'Camisetas',
                'name' => 'Camiseta BLÜK Classic',
                'description' => 'Camiseta de algodón 100% con logo BLÜK bordado. Corte regular de alta durabilidad en color negro.',
                'price' => 19.99,
                'stock' => 50,
                'image' => 'products/camiseta_basic_negra.jpeg'
            ],
            [
                'category' => 'Camisetas',
                'name' => 'Camiseta Basic Blanca',
                'description' => 'Camiseta básica blanca confeccionada en algodón orgánico. Suave al tacto y corte clásico.',
                'price' => 15.99,
                'stock' => 50,
                'image' => 'products/camiseta_basic_blanca.jpeg'
            ],
            [
                'category' => 'Camisetas',
                'name' => 'Camiseta Oversize Urban',
                'description' => 'Corte oversize en color beige con estampado exclusivo Solar x BLÜK. Estilo urbano premium.',
                'price' => 18.50,
                'stock' => 50,
                'image' => 'products/camiseta_beige_solarxbluk.jpeg'
            ],
            [
                'category' => 'Camisetas',
                'name' => 'Camiseta Beige Surf',
                'description' => 'Camiseta color beige inspirada en el surf urbano. Algodón premium y corte regular fit, con estampado frontal y trasero.',
                'price' => 17.99,
                'stock' => 50,
                'image' => 'products/camiseta_beige_surf_1.jpeg',
                'image_back' => 'products/camiseta_beige_surf_2.jpeg'
            ],
            [
                'category' => 'Camisetas',
                'name' => 'Camiseta Azul Ballena',
                'description' => 'Camiseta en color azul marino con ilustraciones originales de ballena en la parte frontal y trasera.',
                'price' => 16.50,
                'stock' => 50,
                'image' => 'products/camiseta_azoscuro_ballena_1.jpeg',
                'image_back' => 'products/camiseta_azoscuro_ballena_2.jpeg'
            ],

            // Accesorios
            [
                'category' => 'Accesorios',
                'name' => 'Gorra BLÜK Logo',
                'description' => 'Gorra ajustable de cinco paneles en color beige con el logo clásico de BLÜK bordado.',
                'price' => 19.99,
                'stock' => 50,
                'image' => 'products/gorra_beige.jpeg',
                'image_back' => null
            ],

            // Bolsos
            [
                'category' => 'Bolsos',
                'name' => 'Bolso Cerezas',
                'description' => 'Bolso de tela resistente con un diseño divertido y colorido de cerezas. Ideal para playa o uso diario.',
                'price' => 12.99,
                'stock' => 50,
                'image' => 'products/bolso_cerezas.jpeg',
                'image_back' => null
            ],
            [
                'category' => 'Bolsos',
                'name' => 'Bolso Hippie',
                'description' => 'Bolso con estilo bohemio y colores veraniegos. Amplio, cómodo y con cierre reforzado.',
                'price' => 13.50,
                'stock' => 50,
                'image' => 'products/bolso_hippie.jpeg',
                'image_back' => null
            ],
            [
                'category' => 'Bolsos',
                'name' => 'Bolso Langosta',
                'description' => 'Bolso temático marinero con estampado de langosta roja sobre fondo claro. Algodón grueso y duradero.',
                'price' => 12.99,
                'stock' => 50,
                'image' => 'products/bolso_langosta.jpeg',
                'image_back' => null
            ],
            [
                'category' => 'Bolsos',
                'name' => 'Bolso Sol',
                'description' => 'Diseño radiante con un sol ilustrado. Perfecto para los días luminosos, lavable y ecológico.',
                'price' => 10.99,
                'stock' => 50,
                'image' => 'products/bolso_sol.jpeg',
                'image_back' => null
            ],
            [
                'category' => 'Bolsos',
                'name' => 'Bolso Tortuga',
                'description' => 'Estampado de tortuga marina en tonos verdes. Confeccionado en loneta ecológica de alta calidad.',
                'price' => 11.99,
                'stock' => 50,
                'image' => 'products/bolso_tortuga.jpeg',
                'image_back' => null
            ],
        ];

        foreach ($products as $item) {
            $category = Category::where('name', $item['category'])->first();

            if ($category) {
                Product::firstOrCreate(
                    ['name' => $item['name']],
                    [
                        'category_id' => $category->id,
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'image' => $item['image'],
                        'image_back' => $item['image_back'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
