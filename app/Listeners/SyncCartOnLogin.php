<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class SyncCartOnLogin
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
     * Fusiona el carrito de sesión (invitado) con el carrito
     * persistente del usuario que acaba de iniciar sesión.
     */
    public function handle(Login $event): void
    {
        $this->cartService->syncSessionCartToDatabase($event->user);
    }
}
