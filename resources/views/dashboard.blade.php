@php
    use App\Models\ImportantDate;
    use App\Models\Speaker;
    use App\Models\Submission;
    use App\Models\ThematicAxis;
    use App\Models\User;

    $totalSubmissions = Submission::count();
    $pendingCount     = Submission::whereIn('status', [Submission::STATUS_PENDING, Submission::STATUS_UNDER_REVIEW])->count();
    $approvedOral     = Submission::where('status', Submission::STATUS_APPROVED)->where('modality', Submission::MODALITY_ORAL)->count();
    $approvedPoster   = Submission::where('status', Submission::STATUS_APPROVED)->where('modality', Submission::MODALITY_POSTER)->count();
    $rejectedCount    = Submission::where('status', Submission::STATUS_REJECTED)->count();

    $recentPending = Submission::with('axis')
        ->whereIn('status', [Submission::STATUS_PENDING, Submission::STATUS_UNDER_REVIEW])
        ->latest()
        ->take(5)
        ->get();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Panel de Control · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    Sesión iniciada como <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->roleLabel() }}@if (Auth::user()->specialty) · {{ Auth::user()->specialty }}@endif)
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if (Auth::user()->canReviewSubmissions())
                    <a href="{{ route('committee.submissions.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Evaluar resúmenes ({{ $pendingCount }} pendientes)
                    </a>
                @endif
                <a href="{{ route('home') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Ver sitio público ↗
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (Auth::user()->canReviewSubmissions())
                {{-- Métricas de Resúmenes --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-slate-600">
                        <div class="text-xs font-semibold uppercase text-gray-500">Total recibidos</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalSubmissions }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-amber-500">
                        <div class="text-xs font-semibold uppercase text-amber-700">Por evaluar</div>
                        <div class="mt-1 text-2xl font-bold text-amber-600">{{ $pendingCount }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-emerald-600">
                        <div class="text-xs font-semibold uppercase text-emerald-700">Aprobados Oral</div>
                        <div class="mt-1 text-2xl font-bold text-emerald-700">{{ $approvedOral }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-teal-600">
                        <div class="text-xs font-semibold uppercase text-teal-700">Aprobados Póster</div>
                        <div class="mt-1 text-2xl font-bold text-teal-700">{{ $approvedPoster }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-red-500">
                        <div class="text-xs font-semibold uppercase text-red-700">No aprobados</div>
                        <div class="mt-1 text-2xl font-bold text-red-600">{{ $rejectedCount }}</div>
                    </div>
                </div>

                {{-- Módulos de gestión --}}
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('committee.submissions.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full mb-2">Comité Científico</span>
                        <h3 class="font-semibold text-lg text-gray-900">Evaluación de Resúmenes</h3>
                        <p class="mt-1 text-sm text-gray-600">Revisar propuestas, aprobar en modalidad Ponencia Oral o Póster, calificar y enviar dictamen al ponente.</p>
                    </a>

                    @if (Auth::user()->is_admin)
                        <a href="{{ route('admin.speakers.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-full mb-2">Administración ({{ Speaker::count() }})</span>
                            <h3 class="font-semibold text-lg text-gray-900">Ponentes Magistrales</h3>
                            <p class="mt-1 text-sm text-gray-600">Crear, editar y eliminar los conferencistas magistrales invitados al congreso.</p>
                        </a>

                        <a href="{{ route('admin.committee.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-amber-50 text-amber-800 rounded-full mb-2">Roles y Permisos</span>
                            <h3 class="font-semibold text-lg text-gray-900">Comité Científico</h3>
                            <p class="mt-1 text-sm text-gray-600">Designar al Líder del Comité Científico y crear cuentas para los evaluadores por eje o programa.</p>
                        </a>

                        <a href="{{ route('admin.axes.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 rounded-full mb-2">{{ ThematicAxis::count() }} Ejes</span>
                            <h3 class="font-semibold text-lg text-gray-900">Ejes y Líneas Temáticas</h3>
                            <p class="mt-1 text-sm text-gray-600">Ajustar los ejes temáticos del congreso y renombrar o añadir sus líneas de investigación.</p>
                        </a>

                        <a href="{{ route('admin.dates.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-purple-50 text-purple-700 rounded-full mb-2">{{ ImportantDate::count() }} Fechas</span>
                            <h3 class="font-semibold text-lg text-gray-900">Fechas Importantes</h3>
                            <p class="mt-1 text-sm text-gray-600">Administrar el cronograma oficial de recepción de resúmenes, resultados y realización del evento.</p>
                        </a>

                        <a href="{{ route('submissions.status') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-400 hover:shadow-md transition">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded-full mb-2">Vista del Postulante</span>
                            <h3 class="font-semibold text-lg text-gray-900">Consulta por Cédula</h3>
                            <p class="mt-1 text-sm text-gray-600">Ver la página donde los ponentes consultan con su cédula si fueron aprobados y descargan la plantilla de póster.</p>
                        </a>
                    @endif
                </div>

                {{-- Últimos resúmenes pendientes --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Resúmenes pendientes de dictamen</h3>
                        <a href="{{ route('committee.submissions.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Radicado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título y Eje</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aspira a</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($recentPending as $s)
                                    <tr>
                                        <td class="px-6 py-3 text-xs font-mono font-semibold text-indigo-700">{{ $s->tracking_code }}</td>
                                        <td class="px-6 py-3 text-sm">
                                            <div class="font-medium text-gray-900">{{ $s->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $s->document_type }} {{ $s->document }} · {{ $s->institution }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-sm">
                                            <div class="text-gray-900 font-medium">{{ $s->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $s->axis?->name ?? '—' }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-700">{{ $s->requestedModalityLabel() }}</td>
                                        <td class="px-6 py-3 text-right text-sm whitespace-nowrap">
                                            <a href="{{ route('committee.submissions.show', $s) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md font-semibold text-xs hover:bg-indigo-100">
                                                Evaluar →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                            ¡Al día! No hay resúmenes pendientes de revisión en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                    Has iniciado sesión. <a href="{{ route('home') }}" class="text-indigo-600 underline">Volver al portal del congreso</a>.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
