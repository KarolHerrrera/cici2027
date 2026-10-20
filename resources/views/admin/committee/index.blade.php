<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comité Científico</h2>
                <p class="text-sm text-gray-500">Gestiona el Líder del Comité Científico y los evaluadores encargados de revisar los resúmenes.</p>
            </div>
            <a href="{{ route('admin.committee.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Nuevo miembro del comité
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Miembro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol en Comité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programa / Especialidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Eje Temático Asignado</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($members as $member)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $member->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($member->is_committee_leader)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            ★ Líder del Comité Científico
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            Evaluador del Comité
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $member->specialty ?: 'General' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $member->axis?->name ?? 'Todos los ejes' }}</td>
                                <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                                    <a href="{{ route('admin.committee.edit', $member) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>
                                    <form action="{{ route('admin.committee.destroy', $member) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar a este miembro del comité?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-4 text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aún no hay miembros del comité. Crea el primero.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
