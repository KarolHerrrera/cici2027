<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel de administración</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('admin.speakers.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg text-gray-900">Ponentes</h3>
                    <p class="mt-1 text-gray-600">Crear, editar y eliminar los ponentes invitados.</p>
                </a>

                <a href="{{ route('home') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg text-gray-900">Ver el sitio</h3>
                    <p class="mt-1 text-gray-600">Abrir la página pública del congreso.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>