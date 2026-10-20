<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ejes Temáticos y Líneas de Investigación</h2>
            <p class="text-sm text-gray-500">Gestiona los ejes temáticos del congreso y las líneas temáticas asociadas a cada uno.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            {{-- Crear nuevo eje --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Agregar nuevo eje temático</h3>
                <form method="POST" action="{{ route('admin.axes.store') }}" class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="name" value="Nombre del eje temático *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 pb-2">
                        <input type="checkbox" name="is_transversal" value="1" class="rounded border-gray-300 text-indigo-600">
                        Eje transversal
                    </label>
                    <x-primary-button>Crear eje</x-primary-button>
                </form>
            </div>

            {{-- Listado de ejes con sus líneas --}}
            @foreach ($axes as $axis)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                        <form method="POST" action="{{ route('admin.axes.update', $axis) }}" class="flex-1 flex flex-wrap items-center gap-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $axis->name }}" required
                                   class="flex-1 min-w-[260px] border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                            <label class="inline-flex items-center gap-1.5 text-xs text-gray-600">
                                <input type="checkbox" name="is_transversal" value="1" @checked($axis->is_transversal) class="rounded border-gray-300 text-indigo-600">
                                Transversal
                            </label>
                            <button type="submit" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold hover:bg-indigo-100">
                                Actualizar eje
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.axes.destroy', $axis) }}"
                              onsubmit="return confirm('¿Eliminar este eje temático y sus líneas?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar eje</button>
                        </form>
                    </div>

                    {{-- Líneas temáticas del eje --}}
                    <div>
                        <h4 class="text-xs font-semibold uppercase text-gray-400 mb-2">
                            Líneas temáticas ({{ $axis->topics->count() }})
                        </h4>
                        <div class="flex flex-wrap gap-2 mb-3">
                            @forelse ($axis->topics as $topic)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $topic->name }}
                                    <form method="POST" action="{{ route('admin.axes.topics.destroy', $topic) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 font-bold" title="Quitar línea">×</button>
                                    </form>
                                </span>
                            @empty
                                <span class="text-xs text-gray-400">Sin líneas registradas en este eje.</span>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('admin.axes.topics.store', $axis) }}" class="flex gap-2 max-w-md">
                            @csrf
                            <input type="text" name="name" placeholder="Nueva línea temática para este eje…" required
                                   class="flex-1 border-gray-300 rounded-md shadow-sm text-xs">
                            <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white rounded-md text-xs font-semibold hover:bg-gray-700">
                                + Añadir línea
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
