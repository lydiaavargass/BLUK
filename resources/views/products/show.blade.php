@extends('layouts.store')

@section('title', $product->name)

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Enlace volver --}}
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                &larr; Volver al catálogo
            </a>
        </div>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
            {{-- Imagen del producto --}}
            <div class="bg-gray-100 rounded-lg overflow-hidden aspect-square">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 italic">Imagen no disponible</div>
                @endif
            </div>

            {{-- Información del producto --}}
            <div class="mt-10 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
                
                <div class="mt-3">
                    <h2 class="sr-only">Información del producto</h2>
                    <p class="text-3xl text-gray-900 font-bold">{{ number_format($product->price, 2, ',', '.') }} €</p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Descripción</h3>
                    <p class="text-base text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div class="mt-6">
                    <div class="flex items-center">
                        <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                            {{ $product->stock > 0 ? 'Stock disponible: ' . $product->stock . ' unidades' : 'Producto agotado' }}
                        </span>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="button" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        Añadir al carrito
                    </button>
                </div>

                {{-- Información adicional básica --}}
                <div class="mt-10 border-t border-gray-200 pt-10">
                    <h3 class="text-sm font-medium text-gray-900">Detalles de envío</h3>
                    <div class="mt-4 prose prose-sm text-gray-500">
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Envío estándar gratuito.</li>
                            <li>Plazo de entrega: 3-5 días hábiles.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
