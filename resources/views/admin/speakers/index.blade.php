<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ponentes</h2>
            <a href="{{ route('admin.speakers.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Nuevo ponente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ponente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Institución</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">País</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Eje</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($speakers as $speaker)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($speaker->photo)
                                            <img src="{{ asset('storage/'.$speaker->photo) }}" alt="Foto de {{ $speaker->name }}"
                                                 class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $speaker->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $speaker->specialty }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $speaker->institution }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $speaker->country }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $speaker->axis?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                                    <a href="{{ route('admin.speakers.edit', $speaker) }}" class="text-indigo-600 hover:underline">Editar</a>
                                    <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar este ponente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-4 text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aún no hay ponentes. Crea el primero.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $speakers->links() }}</div>
        </div>
    </div>
</x-app-layout>