<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Muestra el listado de todos los productos para el administrador.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('buscar')) {
            $query->where('name', 'like', '%' . $request->buscar . '%');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Muestra el formulario para crear un producto nuevo.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Almacena un producto nuevo en la base de datos.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Subida de imagen (si se proporciona)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit(Product $producto): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', [
            'product' => $producto,
            'categories' => $categories,
        ]);
    }

    /**
     * Actualiza un producto existente en la base de datos.
     */
    public function update(UpdateProductRequest $request, Product $producto): RedirectResponse
    {
        $data = $request->validated();

        // Reemplazar imagen si se sube una nueva
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $producto->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Desactiva un producto (eliminación lógica).
     */
    public function destroy(Product $producto): RedirectResponse
    {
        $producto->update(['is_active' => false]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto desactivado correctamente.');
    }
}

