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
            ['category' => 'Camisetas', 'name' => 'Camiseta BLÜK Classic', 'description' => 'Camiseta de algodón 100% con logo BLÜK bordado. Corte regular, disponible en negro y blanco.', 'price' => 29.99, 'stock' => 50],
            ['category' => 'Camisetas', 'name' => 'Camiseta Oversize Urban', 'description' => 'Corte oversize con estampado frontal urbano. Tejido premium de 220g.', 'price' => 34.99, 'stock' => 35],
            ['category' => 'Camisetas', 'name' => 'Camiseta Minimal Stripe', 'description' => 'Diseño minimalista con rayas finas. Algodón orgánico certificado.', 'price' => 27.50, 'stock' => 40],

            // Pantalones
            ['category' => 'Pantalones', 'name' => 'Jogger BLÜK Street', 'description' => 'Jogger de felpa con puños elásticos y bolsillos laterales. Perfecto para el día a día.', 'price' => 44.99, 'stock' => 30],
            ['category' => 'Pantalones', 'name' => 'Pantalón Cargo Wide', 'description' => 'Pantalón cargo de corte ancho con bolsillos utilitarios. Tejido resistente.', 'price' => 54.99, 'stock' => 25],
            ['category' => 'Pantalones', 'name' => 'Vaquero Relaxed Fit', 'description' => 'Denim 100% algodón con lavado medio. Corte relajado y cómodo.', 'price' => 49.99, 'stock' => 20],

            // Zapatillas
            ['category' => 'Zapatillas', 'name' => 'BLÜK Runner V1', 'description' => 'Zapatilla deportiva con suela de goma EVA y upper de malla transpirable.', 'price' => 79.99, 'stock' => 15],
            ['category' => 'Zapatillas', 'name' => 'Sneaker Platform', 'description' => 'Sneaker con plataforma de 4cm. Diseño limpio en piel sintética blanca.', 'price' => 89.99, 'stock' => 12],

            // Accesorios
            ['category' => 'Accesorios', 'name' => 'Gorra BLÜK Logo', 'description' => 'Gorra de cinco paneles con logo bordado. Cierre ajustable trasero.', 'price' => 19.99, 'stock' => 60],
            ['category' => 'Accesorios', 'name' => 'Mochila Urban Pack', 'description' => 'Mochila de 20L con compartimento para portátil hasta 15". Tejido impermeable.', 'price' => 59.99, 'stock' => 18],
            ['category' => 'Accesorios', 'name' => 'Cinturón Nylon Táctico', 'description' => 'Cinturón de nylon con hebilla metálica. Largo ajustable hasta 120cm.', 'price' => 14.99, 'stock' => 45],

            // Sudaderas
            ['category' => 'Sudaderas', 'name' => 'Hoodie BLÜK Essential', 'description' => 'Sudadera con capucha y bolsillo canguro. Felpa interior de 320g.', 'price' => 54.99, 'stock' => 25],
            ['category' => 'Sudaderas', 'name' => 'Sudadera Crewneck Minimal', 'description' => 'Sin capucha, cuello redondo, logo discreto en el pecho. Ideal para layering.', 'price' => 49.99, 'stock' => 30],
            ['category' => 'Sudaderas', 'name' => 'Hoodie Zip Reflective', 'description' => 'Sudadera con cremallera completa y detalles reflectantes. Perfecta para salidas nocturnas.', 'price' => 64.99, 'stock' => 10],
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
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
