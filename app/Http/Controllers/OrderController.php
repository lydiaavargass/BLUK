<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
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
     * Muestra el resumen del carrito antes de confirmar el pedido (checkout).
     */
    public function checkout()
    {
        $cartData = $this->cartService->getCartData();

        if (empty($cartData['products'])) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío. Añade productos antes de continuar.');
        }

        return view('orders.checkout', [
            'products' => $cartData['products'],
            'total' => $cartData['total'],
        ]);
    }

    /**
     * Confirma el pedido: crea order + order_items, descuenta stock y vacía el carrito.
     */
    public function store(Request $request)
    {
        $cartData = $this->cartService->getCartData();

        if (empty($cartData['products'])) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Usamos transacción para garantizar consistencia
        DB::beginTransaction();

        try {
            // 1. Validar stock de cada producto
            foreach ($cartData['products'] as $item) {
                $product = Product::lockForUpdate()->find($item['product']->id);

                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "No hay suficiente stock de '{$product->name}'. Stock disponible: {$product->stock}.");
                }
            }

            // 2. Crear el pedido
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pendiente',
                'total' => $cartData['total'],
            ]);

            // 3. Crear líneas del pedido y descontar stock
            foreach ($cartData['products'] as $item) {
                $product = Product::lockForUpdate()->find($item['product']->id);

                $order->items()->create([
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // 4. Vaciar el carrito
            $this->cartService->clear();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Ocurrió un error al procesar tu pedido. Inténtalo de nuevo.');
        }
    }

    /**
     * Historial de pedidos del usuario autenticado.
     */
    public function index(): View
    {
        $orders = auth()->user()->orders()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detalle de un pedido concreto.
     */
    public function show(Order $order)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
