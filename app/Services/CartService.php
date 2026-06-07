<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Exception;

class CartService
{
    /**
     * Obtiene los productos y el total del carrito,
     * adaptándose automáticamente a si el usuario está autenticado o no.
     */
    public function getCartData(): array
    {
        if (auth()->check()) {
            return $this->getAuthenticatedCartData();
        }

        return $this->getGuestCartData();
    }

    /**
     * Añade un producto al carrito.
     *
     * @throws Exception
     */
    public function store(Product $product, int $quantity): void
    {
        if (!$product->is_active) {
            throw new Exception('Este producto no está disponible.');
        }

        if (auth()->check()) {
            $this->storeAuthenticated($product, $quantity);
        } else {
            $this->storeGuest($product, $quantity);
        }
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     *
     * @throws Exception
     */
    public function update(Product $product, int $quantity): void
    {
        if ($quantity > $product->stock) {
            throw new Exception('No hay suficiente stock disponible.');
        }

        if (auth()->check()) {
            $this->updateAuthenticated($product, $quantity);
        } else {
            $this->updateGuest($product->id, $quantity);
        }
    }

    /**
     * Elimina un producto del carrito.
     */
    public function destroy(int $productId): void
    {
        if (auth()->check()) {
            $this->destroyAuthenticated($productId);
        } else {
            $this->destroyGuest($productId);
        }
    }

    /**
     * Vacía por completo el carrito de compras.
     */
    public function clear(): void
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
        } else {
            session()->forget('cart');
        }
    }

    /**
     * Sincroniza el carrito de sesión (invitado) con el carrito de la base de datos al iniciar sesión.
     */
    public function syncSessionCartToDatabase($user): void
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product || !$product->is_active) {
                continue;
            }

            $cartItem = $cart->items()->where('product_id', $productId)->first();

            if ($cartItem) {
                $newQty = min($cartItem->quantity + $item['quantity'], $product->stock);
                $cartItem->update(['quantity' => $newQty]);
            } else {
                $qty = min($item['quantity'], $product->stock);
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);
            }
        }

        session()->forget('cart');
    }

    // -------------------------------------------------------------
    // Métodos Auxiliares Internos (Guest)
    // -------------------------------------------------------------

    private function getGuestCartData(): array
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product && $product->is_active) {
                $subtotal = $product->price * $item['quantity'];
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return compact('products', 'total');
    }

    /**
     * @throws Exception
     */
    private function storeGuest(Product $product, int $quantity): void
    {
        $cart = session()->get('cart', []);
        $productId = $product->id;

        $currentQty = $cart[$productId]['quantity'] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            throw new Exception('No hay suficiente stock disponible.');
        }

        $cart[$productId] = ['quantity' => $newQty];
        session()->put('cart', $cart);
    }

    private function updateGuest(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
    }

    private function destroyGuest(int $productId): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }

    // -------------------------------------------------------------
    // Métodos Auxiliares Internos (Authenticated)
    // -------------------------------------------------------------

    private function getAuthenticatedCartData(): array
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $items = $cart->items()->with('product')->get();

        $products = [];
        $total = 0;

        foreach ($items as $item) {
            if ($item->product && $item->product->is_active) {
                $subtotal = $item->product->price * $item->quantity;
                $products[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return compact('products', 'total');
    }

    /**
     * @throws Exception
     */
    private function storeAuthenticated(Product $product, int $quantity): void
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        $currentQty = $cartItem ? $cartItem->quantity : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            throw new Exception('No hay suficiente stock disponible.');
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }

    private function updateAuthenticated(Product $product, int $quantity): void
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        }
    }

    private function destroyAuthenticated(int $productId): void
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->items()->where('product_id', $productId)->delete();
        }
    }
}
