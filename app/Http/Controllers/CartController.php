<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    /**
     * Inyecta la capa de servicio para el carrito.
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Muestra el contenido del carrito.
     */
    public function index(): View
    {
        $cartData = $this->cartService->getCartData();

        return view('cart.index', [
            'products' => $cartData['products'],
            'total' => $cartData['total'],
        ]);
    }

    /**
     * Añade un producto al carrito.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        try {
            $this->cartService->store($product, $request->quantity);

            return redirect()->route('cart.index')
                ->with('success', 'Producto añadido al carrito.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        try {
            $this->cartService->update($product, $request->quantity);

            return back()->with('success', 'Cantidad actualizada.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Elimina un producto del carrito.
     */
    public function destroy(int $productId): RedirectResponse
    {
        $this->cartService->destroy($productId);

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
