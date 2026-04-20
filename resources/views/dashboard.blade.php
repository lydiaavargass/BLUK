<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de BLÜK') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="space-y-4 p-6 text-gray-900">
                    <p class="text-lg font-semibold">Sesión iniciada correctamente.</p>
                    <p class="text-sm text-gray-600">La base del proyecto ya está preparada para continuar con el MVP de BLÜK sobre la arquitectura MVC de Laravel, sin meter lógica de negocio extra en esta primera semana.</p>
                    <a href="{{ route('home') }}" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
