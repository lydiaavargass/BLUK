@extends('layouts.admin')

@section('page-title', 'Editar Producto')

@section('content')
    <div class="max-w-3xl">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Información básica --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Información básica</h2>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del producto</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" id="description" rows="4"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="category_id" id="category_id"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Precio y Stock --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Precio y stock</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio (€)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                               step="0.01" min="0.01"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock (unidades)</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                               min="0"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Imagen --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Imagen</h2>

                @if ($product->image)
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-lg border border-gray-200 overflow-hidden bg-gray-100">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-cover w-full h-full">
                        </div>
                        <p class="text-sm text-gray-500">Imagen actual. Sube una nueva para reemplazarla.</p>
                    </div>
                @endif

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $product->image ? 'Reemplazar imagen' : 'Imagen del producto' }}
                    </label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG o WebP. Máximo 2 MB.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Estado --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', $product->is_active))>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700">Producto activo (visible en la tienda)</span>
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg text-sm font-medium shadow-sm transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
