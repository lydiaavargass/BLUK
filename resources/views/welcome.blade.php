<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'BLÜK') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans text-slate-100 antialiased">
        <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
            <header class="border-b border-white/10">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                    <a href="{{ route('home') }}" class="text-xl font-bold tracking-[0.3em] text-white">BLÜK</a>

                    <nav class="flex items-center gap-3 text-sm font-medium">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-full border border-cyan-400/40 px-4 py-2 text-cyan-200 transition hover:border-cyan-300 hover:text-white">
                                Mi panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-slate-200 transition hover:bg-white/5 hover:text-white">
                                Iniciar sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-cyan-400 px-4 py-2 font-semibold text-slate-950 transition hover:bg-cyan-300">
                                    Crear cuenta
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <main>
                <section class="mx-auto max-w-6xl px-6 py-16 md:py-24">
                    <div class="grid gap-10 lg:grid-cols-[1.3fr_0.7fr] lg:items-center">
                        <div>
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Semana 1 · Base lista</p>
                            <h1 class="max-w-3xl text-4xl font-bold leading-tight text-white md:text-6xl">
                                BLÜK, tienda online académica preparada para arrancar sobre Laravel 11.
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                                Este home provisional deja lista la base funcional del MVP y arranca sobre la arquitectura MVC de Laravel: autenticación con Breeze, entorno Docker con Sail y una estructura clara para seguir construyendo catálogo, carrito y pedidos en las próximas semanas.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-4">
                                @guest
                                    <a href="{{ route('register') }}" class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                                        Empezar con una cuenta
                                    </a>
                                    <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 font-semibold text-white transition hover:bg-white/5">
                                        Ya tengo cuenta
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="rounded-full bg-cyan-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                                        Ir al panel
                                    </a>
                                @endguest
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-cyan-950/20">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">Estado actual</p>

                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-4">
                                    <h2 class="font-semibold text-white">Autenticación</h2>
                                    <p class="mt-2 text-sm text-slate-300">Login, registro y logout disponibles con Laravel Breeze en stack Blade.</p>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-4">
                                    <h2 class="font-semibold text-white">Entorno</h2>
                                    <p class="mt-2 text-sm text-slate-300">Laravel Sail configurado con MySQL para el arranque estándar del proyecto.</p>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-4">
                                    <h2 class="font-semibold text-white">Siguiente foco</h2>
                                    <p class="mt-2 text-sm text-slate-300">Semana 2 podrá continuar con catálogo y estructura de datos sin rehacer la base.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
