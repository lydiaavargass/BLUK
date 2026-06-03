@extends('layouts.store')

@section('title', 'Bienvenidos a BLÜK')

@section('content')
{{-- Estructura de la landing page con bloques de color inspirados en el Surf y Skate --}}
<div class="welcome-container bg-white">
    
    {{-- Hero Section - Imagen de fondo surf/skate difuminada con overlay de azul marino/navy profundo --}}
    <div class="relative py-24 sm:py-32 overflow-hidden border-b border-indigo-950/20 bg-cover bg-center" style="background-image: linear-gradient(180deg, rgba(12, 30, 54, 0.85) 0%, rgba(8, 20, 36, 0.95) 100%), url('{{ asset('hero-bg.png') }}');">
        {{-- Sutil brillo naranja/coral de fondo --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,107,107,0.12),transparent_50%)]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-[#FF6B6B]/10 text-[#FF6B6B] border border-[#FF6B6B]/20 mb-6 tracking-widest uppercase">
                Surf & Skate Culture
            </span>
            
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight text-white leading-none uppercase">
                Bienvenidos a BLÜK
            </h1>
            
            <p class="mt-6 max-w-xl mx-auto text-lg sm:text-xl text-zinc-300 font-normal leading-relaxed">
                Diseños únicos, duraderos y con estilo.
            </p>
            
            <div class="mt-10 flex justify-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-sm font-bold rounded-xl text-white bg-[#FF6B6B] hover:bg-[#FF5252] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6B6B] shadow-md hover:shadow-lg transition duration-200 transform hover:-translate-y-0.5">
                    Ver Catálogo Completo
                </a>
            </div>
        </div>
    </div>

    {{-- Sección: Lo Nuevo - Fondo color arena/playa cálido (#FAF5EE) que rompe con el blanco --}}
    <div class="bg-[#FAF5EE] py-20 sm:py-24 border-b border-zinc-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pb-6 mb-12 flex items-end justify-between border-b border-zinc-200">
                <div>
                    <span class="text-xs font-bold text-teal-600 tracking-widest uppercase block mb-1">New Arrivals</span>
                    <h2 class="text-3xl font-black tracking-tight text-gray-900 uppercase">Lo Nuevo</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-bold text-teal-600 hover:text-teal-500 transition flex items-center gap-1 group">
                    Ver todo 
                    <span class="group-hover:translate-x-1 transition duration-150">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($loNuevo as $product)
                    <div class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 overflow-hidden flex flex-col justify-between transform">
                        <div>
                            {{-- Contenedor de Imagen o Fallback idéntico al catálogo --}}
                            <div class="aspect-square bg-gray-50 overflow-hidden relative border-b border-gray-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-400 select-none">
                                        <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-bold tracking-wider uppercase">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 uppercase tracking-wider mb-2">
                                    {{ $product->category->name ?? 'Producto' }}
                                </span>
                                <h3 class="text-base font-bold text-gray-900 mt-1 group-hover:text-teal-600 transition">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-lg font-extrabold text-gray-950 mt-2">
                                    {{ number_format($product->price, 2, ',', '.') }} €
                                </p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="mt-2 border-t border-gray-100 pt-4 flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }} mr-2"></span>
                                    {{ $product->stock > 0 ? 'En stock' : 'Agotado' }}
                                </span>
                                <a href="{{ route('products.show', $product) }}" class="text-xs font-bold text-gray-900 hover:text-teal-600 transition flex items-center gap-1">
                                    Ver detalles <span class="text-gray-400">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500 bg-white rounded-2xl border border-gray-200 shadow-sm">
                        No hay productos nuevos disponibles en este momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sección: Más Vendidos - Fondo color celeste mar suave (#EEF6F8) para continuar el dinamismo --}}
    <div class="bg-[#EEF6F8] py-20 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pb-6 mb-12 border-b border-zinc-200">
                <span class="text-xs font-bold text-teal-600 tracking-widest uppercase block mb-1">Top Rated</span>
                <h2 class="text-3xl font-black tracking-tight text-gray-900 uppercase">Más Vendidos</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($masVendidos as $product)
                    <div class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 overflow-hidden flex flex-col justify-between transform">
                        <div>
                            {{-- Contenedor de Imagen o Fallback idéntico al catálogo --}}
                            <div class="aspect-square bg-gray-50 overflow-hidden relative border-b border-gray-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-400 select-none">
                                        <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-bold tracking-wider uppercase">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 uppercase tracking-wider mb-2">
                                    {{ $product->category->name ?? 'Producto' }}
                                </span>
                                <h3 class="text-base font-bold text-gray-900 mt-1 group-hover:text-teal-600 transition">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-lg font-extrabold text-gray-950 mt-2">
                                    {{ number_format($product->price, 2, ',', '.') }} €
                                </p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="mt-2 border-t border-gray-100 pt-4 flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }} mr-2"></span>
                                    {{ $product->stock > 0 ? 'En stock' : 'Agotado' }}
                                </span>
                                <a href="{{ route('products.show', $product) }}" class="text-xs font-bold text-gray-900 hover:text-teal-600 transition flex items-center gap-1">
                                    Ver detalles <span class="text-gray-400">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500 bg-gray-50 rounded-2xl border border-gray-200">
                        No hay productos destacados disponibles en este momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
