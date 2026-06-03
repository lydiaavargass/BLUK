@extends('layouts.store')

@section('title', 'Bienvenidos a BLÜK')

@section('content')
{{-- Hero Section - Estilo minimalista y coherente con el catálogo --}}
<div class="bg-white border-b border-gray-200 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-4 tracking-wider uppercase">
            Surf & Skate Culture
        </span>
        
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight uppercase">
            Bienvenidos a BLÜK
        </h1>
        
        <p class="mt-4 max-w-xl mx-auto text-base sm:text-lg text-gray-600 font-normal leading-relaxed">
            Diseños únicos, duraderos y con estilo.
        </p>
        
        <div class="mt-8 flex justify-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition duration-150">
                Ver Catálogo Completo
            </a>
        </div>
    </div>
</div>

{{-- Sección: Lo Nuevo --}}
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-b border-gray-200 pb-5 mb-10 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Lo Nuevo</h2>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition">Ver todo &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($loNuevo as $product)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        {{-- Contenedor de Imagen o Fallback idéntico al catálogo --}}
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
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="mt-4 border-t border-gray-100 pt-4 flex items-center justify-between">
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
                <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-lg border border-gray-200 shadow-sm">
                    No hay productos nuevos disponibles en este momento.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Sección: Más Vendidos --}}
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-b border-gray-200 pb-5 mb-10">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Más Vendidos</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($masVendidos as $product)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        {{-- Contenedor de Imagen o Fallback idéntico al catálogo --}}
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
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="mt-4 border-t border-gray-100 pt-4 flex items-center justify-between">
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
                <div class="col-span-full text-center py-12 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                    No hay productos destacados disponibles en este momento.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
