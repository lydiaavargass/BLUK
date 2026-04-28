<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Muestra el catálogo de productos con filtrado por categoría.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();

        $query = Product::where('is_active', true);

        if ($request->filled('categoria')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->categoria);
            });
        }

        $products = $query->latest()->paginate(12);

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->categoria,
        ]);
    }

    /**
     * Muestra el detalle de un producto.
     */
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('products.show', [
            'product' => $product,
        ]);
    }
}
