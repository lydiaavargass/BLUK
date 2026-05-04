@extends('layouts.store')

@section('title', 'Carrito de compra')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de compra</h1>

        {{-- Mensajes flash --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(count($products) > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-16 w-16 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                            @if($item['product']->image)
                                                <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs italic">Sin imagen</div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('products.show', $item['product']) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $item['product']->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($item['product']->price, 2, ',', '.') }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Actualizar</button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($item['subtotal'], 2, ',', '.') }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('cart.destroy', $item['product']->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Total y acciones --}}
                <div class="border-t border-gray-200 px-6 py-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total: {{ number_format($total, 2, ',', '.') }} €</span>
                    <div class="flex gap-4">
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Seguir comprando</a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Tu carrito está vacío.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900 font-medium">Ver catálogo</a>
            </div>
        @endif
    </div>
</div>
@endsection
