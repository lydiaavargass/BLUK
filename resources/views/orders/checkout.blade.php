@extends('layouts.store')

@section('title', 'Confirmar pedido')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Confirmar pedido</h1>

        {{-- Mensajes flash --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Resumen del pedido --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Resumen de tu compra</h2>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                        @if($item['product']->image)
                                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs italic">Sin img</div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $item['product']->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($item['product']->price, 2, ',', '.') }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['quantity'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($item['subtotal'], 2, ',', '.') }} €
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total y botón de confirmar --}}
            <div class="border-t border-gray-200 px-6 py-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">Total: {{ number_format($total, 2, ',', '.') }} €</span>

                    <div class="flex gap-4 items-center">
                        <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                            ← Volver al carrito
                        </a>
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                                Confirmar pedido
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
