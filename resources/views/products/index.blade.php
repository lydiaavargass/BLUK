@extends('layouts.store')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Nuestro Catálogo</h1>

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            {{-- Categorías lateral --}}
            <div class="col-span-1">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Filtrar por Categoría</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="{{ !$currentCategory ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-500' }}">
                            Todas las categorías
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.index', ['categoria' => $category->name]) }}" 
                               class="{{ $currentCategory === $category->name ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-500' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Grid de productos --}}
            <div class="mt-8 lg:mt-0 lg:col-span-3">
                {{-- Buscador y Ordenamiento --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex gap-2">
                        @if($currentCategory)
                            <input type="hidden" name="categoria" value="{{ $currentCategory }}">
                        @endif
                        @if($order)
                            <input type="hidden" name="ordenar" value="{{ $order }}">
                        @endif
                        <input type="text" name="buscar" value="{{ $search ?? '' }}" placeholder="Buscar productos..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">Buscar</button>
                    </form>

                    <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-2">
                        @if($currentCategory)
                            <input type="hidden" name="categoria" value="{{ $currentCategory }}">
                        @endif
                        @if($search)
                            <input type="hidden" name="buscar" value="{{ $search }}">
                        @endif
                        <label for="ordenar" class="text-sm font-medium text-gray-700 whitespace-nowrap">Ordenar por:</label>
                        <select name="ordenar" id="ordenar" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Relevancia</option>
                            <option value="precio_asc" {{ ($order ?? '') === 'precio_asc' ? 'selected' : '' }}>Precio: Menor a mayor</option>
                            <option value="precio_desc" {{ ($order ?? '') === 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a menor</option>
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <div class="aspect-square bg-gray-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 italic">Sin imagen</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <span class="text-xs font-semibold text-indigo-600 uppercase">{{ $product->category->name ?? 'Producto' }}</span>
                                <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $product->name }}</h3>
                                <p class="text-xl font-semibold text-gray-900 mt-2">{{ number_format($product->price, 2, ',', '.') }} €</p>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $product->stock > 0 ? 'En stock' : 'Agotado' }}
                                    </span>
                                    <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                        Ver detalles &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-500">
                            No se han encontrado productos.
                        </div>
                    @endforelse
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
