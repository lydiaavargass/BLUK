<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin — {{ config('app.name', 'BLÜK') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex">

            {{-- Sidebar --}}
            <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
                {{-- Logo --}}
                <div class="h-16 flex items-center px-6 border-b border-slate-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold tracking-[0.3em]">
                        BLÜK <span class="text-xs font-normal tracking-normal text-slate-400">Admin</span>
                    </a>
                </div>

                {{-- Navegación --}}
                <nav class="flex-1 px-4 py-6 space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25"/></svg>
                        Productos
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                        Usuarios
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                        Pedidos
                    </a>
                </nav>

                {{-- Footer sidebar --}}
                <div class="px-4 py-4 border-t border-slate-700">
                    <div class="flex items-center gap-3 px-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('home') }}" class="flex-1 text-center text-xs text-slate-400 hover:text-white px-2 py-1.5 rounded bg-slate-800 transition">
                            Ver tienda
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-xs text-slate-400 hover:text-white px-2 py-1.5 rounded bg-slate-800 transition">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Contenido principal --}}
            <div class="flex-1 flex flex-col">
                {{-- Top bar --}}
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
                    <h1 class="text-lg font-semibold text-gray-800">
                        @yield('page-title', 'Panel de Administración')
                    </h1>

                    <div class="flex items-center gap-4">
                        @yield('header-actions')
                    </div>
                </header>

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="mx-6 mt-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-6 mt-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Contenido de la página --}}
                <main class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>

        </div>
    </body>
</html>
