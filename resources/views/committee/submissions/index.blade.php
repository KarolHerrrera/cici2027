<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Evaluación de Resúmenes · Comité Científico
                </h2>
                <p class="text-sm text-gray-500">
                    Revisa las postulaciones recibidas, define si se aprueban en modalidad <strong>Ponencia oral</strong> o <strong>Póster</strong> y notifica a los autores.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('committee.submissions.export') }}"
                   class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Exportar CSV (Excel)
                </a>
                <a href="{{ route('committee.submissions.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    + Registrar resumen
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4 text-green-800 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tarjetas de estadísticas --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                <a href="{{ route('committee.submissions.index') }}" class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-slate-600 hover:shadow transition">
                    <div class="text-xs font-semibold uppercase text-gray-500">Total resúmenes</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                </a>
                <a href="{{ route('committee.submissions.index', ['status' => 'pending']) }}" class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-amber-500 hover:shadow transition">
                    <div class="text-xs font-semibold uppercase text-amber-700">Por evaluar</div>
                    <div class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                </a>
                <a href="{{ route('committee.submissions.index', ['status' => 'approved', 'modality' => 'oral']) }}" class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-emerald-600 hover:shadow transition">
                    <div class="text-xs font-semibold uppercase text-emerald-700">Aprobados Oral</div>
                    <div class="mt-1 text-2xl font-bold text-emerald-700">{{ $stats['approved_oral'] }}</div>
                </a>
                <a href="{{ route('committee.submissions.index', ['status' => 'approved', 'modality' => 'poster']) }}" class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-teal-600 hover:shadow transition">
                    <div class="text-xs font-semibold uppercase text-teal-700">Aprobados Póster</div>
                    <div class="mt-1 text-2xl font-bold text-teal-700">{{ $stats['approved_poster'] }}</div>
                </a>
                <a href="{{ route('committee.submissions.index', ['status' => 'rejected']) }}" class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500 hover:shadow transition">
                    <div class="text-xs font-semibold uppercase text-red-700">No aprobados</div>
                    <div class="mt-1 text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                </a>
            </div>

            {{-- Buscador y filtros --}}
            <form method="GET" action="{{ route('committee.submissions.index') }}" class="bg-white shadow-sm sm:rounded-lg p-4">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5 items-end">
                    <div class="lg:col-span-2">
                        <label for="q" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar (cédula, radicado, autor o título)</label>
                        <input id="q" name="q" type="text" value="{{ $search }}"
                               placeholder="Ej. 1085200101, CICI-2027-0001 o título..."
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Estado</label>
                        <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los estados</option>
                            <option value="pending" @selected($status === 'pending')>Pendientes</option>
                            <option value="under_review" @selected($status === 'under_review')>En evaluación</option>
                            <option value="approved" @selected($status === 'approved')>Aprobados</option>
                            <option value="rejected" @selected($status === 'rejected')>No aprobados</option>
                        </select>
                    </div>

                    <div>
                        <label for="axis_id" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Eje temático</label>
                        <select id="axis_id" name="axis_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los ejes</option>
                            @foreach ($axes as $axis)
                                <option value="{{ $axis->id }}" @selected((string) $axisId === (string) $axis->id)>
                                    {{ \Illuminate\Support\Str::limit($axis->name, 42) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-indigo-700">
                            Filtrar
                        </button>
                        @if ($search || $status || $axisId || $modality)
                            <a href="{{ route('committee.submissions.index') }}" class="px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold uppercase rounded-md hover:bg-gray-200">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Tabla de resúmenes --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Radicado</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulante / Cédula</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título y Eje Temático</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado y Modalidad</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($submissions as $s)
                            @php
                                $pillClass = match ($s->status) {
                                    'approved'     => 'bg-green-100 text-green-800 border-green-200',
                                    'rejected'     => 'bg-red-100 text-red-800 border-red-200',
                                    'under_review' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    default        => 'bg-amber-100 text-amber-800 border-amber-200',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded">
                                        {{ $s->tracking_code }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">{{ $s->created_at?->format('d/m/Y') }}</div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-900">{{ $s->name }}</div>
                                    <div class="text-xs text-gray-600">{{ $s->document_type }} {{ $s->document }} · {{ $s->email }}</div>
                                    @if ($s->institution)
                                        <div class="text-xs text-gray-500">{{ $s->institution }}@if ($s->city) ({{ $s->city }})@endif</div>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $s->title }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $s->axis?->name ?? 'Sin eje' }}
                                        @if ($s->topic) · <em>{{ $s->topic->name }}</em> @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        Modalidad solicitada: <strong>{{ $s->requestedModalityLabel() }}</strong>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $pillClass }}">
                                        {{ $s->statusShortLabel() }}
                                    </span>
                                    @if ($s->modalityLabel())
                                        <div class="text-xs font-bold text-gray-800 mt-1">
                                            {{ $s->modalityLabel() }}
                                        </div>
                                    @endif
                                    @if ($s->score !== null)
                                        <div class="text-xs text-gray-500">Puntaje: {{ $s->score }}/100</div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right text-sm whitespace-nowrap">
                                    <a href="{{ route('committee.submissions.show', $s) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                                        Revisar / Dictaminar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No se encontraron resúmenes con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $submissions->links() }}</div>
        </div>
    </div>
</x-app-layout>
