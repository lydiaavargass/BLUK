<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'BLÜK'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col">

            {{-- Navegación --}}
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            {{-- Logo --}}
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider">
                                    BLÜK
                                </a>
                            </div>

                            {{-- Enlaces --}}
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Inicio
                                </a>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('products.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Catálogo
                                </a>
                                <a href="{{ route('cart.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('cart.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Carrito
                                </a>
                            </div>
                        </div>

                        {{-- Usuario --}}
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Mi Panel</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">Registro</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Contenido --}}
            <main class="flex-1">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-200 mt-auto">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Proyecto BLÜK</h3>
                            <p class="mt-4 text-base text-gray-500">Tienda online de ropa y accesorios. Proyecto final DAW.</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Enlaces</h3>
                            <ul class="mt-4 space-y-4 text-base text-gray-500">
                                <li><a href="{{ route('products.index') }}" class="hover:text-gray-900">Ver Productos</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Defensa</h3>
                            <p class="mt-4 text-base text-gray-500">Trabajo realizado por Lydia y Xavi.</p>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-200 pt-8 text-center text-gray-400 text-sm">
                        &copy; {{ date('Y') }} BLÜK. Todos los derechos reservados.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
