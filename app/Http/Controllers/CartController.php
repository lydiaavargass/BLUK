<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Muestra el contenido del carrito.
     */
    public function index()
    {
        if (auth()->check()) {
            return $this->indexAuthenticated();
        }

        return $this->indexGuest();
    }

    /**
     * Añade un producto al carrito.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->is_active) {
            return back()->with('error', 'Este producto no está disponible.');
        }

        if (auth()->check()) {
            return $this->storeAuthenticated($product, $request->quantity);
        }

        return $this->storeGuest($product, $request->quantity);
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function update(Request $request, int $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        if (auth()->check()) {
            return $this->updateAuthenticated($product, $request->quantity);
        }

        return $this->updateGuest($product->id, $request->quantity);
    }

    /**
     * Elimina un producto del carrito.
     */
    public function destroy(int $productId)
    {
        if (auth()->check()) {
            return $this->destroyAuthenticated($productId);
        }

        return $this->destroyGuest($productId);
    }

    // -------------------------------------------------------
    // Métodos para invitado (sesión)
    // -------------------------------------------------------

    private function indexGuest()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('products', 'total'));
    }

    private function storeGuest(Product $product, int $quantity)
    {
        $cart = session()->get('cart', []);
        $productId = $product->id;

        $currentQty = $cart[$productId]['quantity'] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $cart[$productId] = ['quantity' => $newQty];
        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Producto añadido al carrito.');
    }

    private function updateGuest(int $productId, int $quantity)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cantidad actualizada.');
    }

    private function destroyGuest(int $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    // -------------------------------------------------------
    // Métodos para usuario autenticado (BD)
    // -------------------------------------------------------

    private function indexAuthenticated()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $items = $cart->items()->with('product')->get();

        $products = [];
        $total = 0;

        foreach ($items as $item) {
            if ($item->product) {
                $subtotal = $item->product->price * $item->quantity;
                $products[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('products', 'total'));
    }

    private function storeAuthenticated(Product $product, int $quantity)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        $currentQty = $cartItem ? $cartItem->quantity : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Producto añadido al carrito.');
    }

    private function updateAuthenticated(Product $product, int $quantity)
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        }

        return back()->with('success', 'Cantidad actualizada.');
    }

    private function destroyAuthenticated(int $productId)
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->items()->where('product_id', $productId)->delete();
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
