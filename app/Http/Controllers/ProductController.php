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

        if ($request->filled('buscar')) {
            $query->where('name', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('ordenar')) {
            if ($request->ordenar === 'precio_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->ordenar === 'precio_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->categoria,
            'search' => $request->buscar,
            'order' => $request->ordenar,
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
