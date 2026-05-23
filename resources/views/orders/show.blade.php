@extends('layouts.store')

@section('title', 'Pedido #' . $order->id)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Mensajes flash --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Cabecera --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                ← Volver a mis pedidos
            </a>
        </div>

        {{-- Info del pedido --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Información del pedido</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1">
                        @php
                            $badgeClasses = match($order->status) {
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'procesando' => 'bg-blue-100 text-blue-800',
                                'enviado' => 'bg-green-100 text-green-800',
                                'cancelado' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total</dt>
                    <dd class="mt-1 text-sm font-bold text-gray-900">{{ number_format($order->total, 2, ',', '.') }} €</dd>
                </div>
            </div>
        </div>

        {{-- Productos del pedido --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Productos</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio unitario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs italic">Sin img</div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $item->product->name ?? 'Producto no disponible' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($item->price, 2, ',', '.') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($item->price * $item->quantity, 2, ',', '.') }} €
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total --}}
            <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                <span class="text-xl font-bold text-gray-900">Total: {{ number_format($order->total, 2, ',', '.') }} €</span>
            </div>
        </div>
    </div>
</div>
@endsection
