<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Listado de pedidos paginados con relaciones.
     */
    public function index(): View
    {
        $orders = Order::with(['user', 'items'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Vista de detalle de un pedido.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Actualiza el estado de un pedido y restaura el stock si se cancela.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pendiente,procesando,enviado,cancelado',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        if ($newStatus === $oldStatus) {
            return redirect()->back()->with('info', 'El pedido ya tiene ese estado.');
        }

        DB::beginTransaction();
        try {
            if ($newStatus === 'cancelado' && $oldStatus !== 'cancelado') {
                // Restaurar stock
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => $newStatus,
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'El estado del pedido ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el pedido: ' . $e->getMessage());
        }
    }
}
