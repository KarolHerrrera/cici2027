<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cronograma de Fechas Importantes</h2>
            <p class="text-sm text-gray-500">Agrega, modifica o elimina las fechas oficiales que se muestran en la página principal del congreso.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            {{-- Agregar nueva fecha --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Agregar nueva etapa o fecha</h3>
                <form method="POST" action="{{ route('admin.dates.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
                    @csrf
                    <div class="sm:col-span-2">
                        <x-input-label for="title" value="Título de la etapa *" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" placeholder="Ej. Cierre de recepción de resúmenes" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date" value="Fecha inicial *" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="Fecha final (opcional)" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-3 flex items-center gap-2">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600">
                        <label for="is_featured" class="text-sm text-gray-700">Destacar esta fecha en la línea de tiempo (punto naranja)</label>
                    </div>
                    <div class="text-right">
                        <x-primary-button>Agregar fecha</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Lista de fechas existentes --}}
            <div class="bg-white shadow-sm sm:rounded-lg divide-y divide-gray-200">
                @forelse ($dates as $d)
                    <div class="p-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <form method="POST" action="{{ route('admin.dates.update', $d) }}" class="flex-1 grid grid-cols-1 gap-3 sm:grid-cols-5 items-center">
                            @csrf
                            @method('PUT')
                            <div class="sm:col-span-2">
                                <input type="text" name="title" value="{{ $d->title }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm font-medium">
                            </div>
                            <div>
                                <input type="date" name="date" value="{{ $d->date?->format('Y-m-d') }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div>
                                <input type="date" name="end_date" value="{{ $d->end_date?->format('Y-m-d') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <label class="inline-flex items-center gap-1.5 text-xs text-gray-600">
                                    <input type="checkbox" name="is_featured" value="1" @checked($d->is_featured) class="rounded border-gray-300 text-indigo-600">
                                    Destacada
                                </label>
                                <button type="submit" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold hover:bg-indigo-100">
                                    Guardar
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.dates.destroy', $d) }}"
                              onsubmit="return confirm('¿Eliminar esta fecha?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No hay fechas registradas.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
