<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Auth\Events\Login;

class SyncCartOnLogin
{
    /**
     * Fusiona el carrito de sesión (invitado) con el carrito
     * persistente del usuario que acaba de iniciar sesión.
     */
    public function handle(Login $event): void
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        $user = $event->user;
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);

            if (! $product) {
                continue;
            }

            $cartItem = $cart->items()->where('product_id', $productId)->first();

            if ($cartItem) {
                // Sumar cantidades sin exceder el stock
                $newQty = min($cartItem->quantity + $item['quantity'], $product->stock);
                $cartItem->update(['quantity' => $newQty]);
            } else {
                // Añadir nuevo ítem sin exceder stock
                $qty = min($item['quantity'], $product->stock);
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);
            }
        }

        // Limpiar carrito de sesión
        session()->forget('cart');
    }
}
