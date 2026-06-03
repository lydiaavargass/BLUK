@extends('layouts.admin')

@section('page-title', 'Detalle del Pedido #' . $order->id)

@section('header-actions')
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
        <svg class="mr-2 -ml-1 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver a Pedidos
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Detalle del Pedido (2 columnas en pantallas grandes) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Info General y Estado --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Resumen del Pedido</h2>
                        <p class="text-sm text-gray-500">Realizado el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
                    </div>
                    <div>
                        @switch($order->status)
                            @case('pendiente')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                                    Pendiente
                                </span>
                                @break
                            @case('procesando')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    Procesando
                                </span>
                                @break
                            @case('enviado')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                                    Enviado
                                </span>
                                @break
                            @case('cancelado')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-rose-100 text-rose-800">
                                    Cancelado
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                        @endswitch
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between text-sm">
                    <span class="text-gray-500">Total pagado:</span>
                    <span class="font-bold text-gray-900 text-base">{{ number_format($order->total, 2) }} €</span>
                </div>
            </div>

            {{-- Tabla de Productos --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Productos en este pedido</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($item->product && $item->product->image)
                                                <img class="w-10 h-10 rounded-lg object-cover bg-gray-100" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                @if ($item->product)
                                                    <a href="{{ route('admin.products.edit', $item->product) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <div class="text-xs text-gray-500">Stock actual: {{ $item->product->stock }}</div>
                                                @else
                                                    <span class="text-sm font-semibold text-gray-500 italic">Producto eliminado</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                                        {{ number_format($item->price, 2) }} €
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-semibold">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 font-semibold">
                                        {{ number_format($item->price * $item->quantity, 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Panel Lateral: Estado del Pedido y Cliente --}}
        <div class="space-y-6">
            
            {{-- Gestión del Estado --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Gestionar Pedido</h3>
                
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Estado del Pedido</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="pendiente" {{ $order->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="procesando" {{ $order->status === 'procesando' ? 'selected' : '' }}>Procesando</option>
                            <option value="enviado" {{ $order->status === 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="cancelado" {{ $order->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @if ($order->status === 'cancelado')
                            <p class="text-xs text-rose-600 mt-2">Este pedido está cancelado. Cambiar el estado no volverá a descontar el stock automáticamente.</p>
                        @else
                            <p class="text-xs text-gray-500 mt-2">Si cambias el estado a "Cancelado", se restaurará automáticamente el stock de los productos asociados.</p>
                        @endif
                    </div>
                    
                    <button type="submit" class="w-full bg-slate-900 text-white py-2 px-4 rounded-lg font-semibold text-sm hover:bg-slate-800 transition duration-150 shadow-sm">
                        Actualizar Estado
                    </button>
                </form>
            </div>

            {{-- Información del Cliente --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Información del Cliente</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold">
                            {{ substr($order->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">{{ $order->user->name }}</h4>
                            <p class="text-xs text-gray-500">ID de Usuario: #{{ $order->user->id }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email:</span>
                            <span class="text-gray-900 font-semibold">{{ $order->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Miembro desde:</span>
                            <span class="text-gray-900 font-semibold">{{ $order->user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
