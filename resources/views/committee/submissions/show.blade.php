<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold bg-indigo-100 text-indigo-800 px-2.5 py-1 rounded">
                        {{ $submission->tracking_code }}
                    </span>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Evaluación de Resumen</h2>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('submissions.status', ['documento' => $submission->document]) }}"
                   target="_blank"
                   class="text-xs font-semibold text-indigo-600 hover:underline">
                    Ver vista pública del postulante (CC {{ $submission->document }}) ↗
                </a>
                <a href="{{ route('committee.submissions.index') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    ← Volver a la lista
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4 text-green-800 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Ficha del resumen y autor --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wide text-indigo-600">
                            {{ $submission->axis?->name ?? 'Sin eje temático asignado' }}
                            @if ($submission->topic) · {{ $submission->topic->name }} @endif
                        </span>
                        <h3 class="mt-1 font-bold text-xl text-gray-900">{{ $submission->title }}</h3>
                    </div>

                    @php
                        $badgeColor = match ($submission->status) {
                            'approved'     => 'bg-green-100 text-green-800 border-green-300',
                            'rejected'     => 'bg-red-100 text-red-800 border-red-300',
                            'under_review' => 'bg-blue-100 text-blue-800 border-blue-300',
                            default        => 'bg-amber-100 text-amber-800 border-amber-300',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                        {{ $submission->statusLabel() }}
                        @if ($submission->modalityLabel()) · {{ $submission->modalityLabel() }} @endif
                    </span>
                </div>

                <dl class="mt-5 grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Autor principal</dt>
                        <dd class="mt-0.5 font-semibold text-gray-900">{{ $submission->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Documento (Cédula)</dt>
                        <dd class="mt-0.5 font-mono font-semibold text-gray-900">{{ $submission->document_type }} {{ $submission->document }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Correo y Teléfono</dt>
                        <dd class="mt-0.5 text-gray-900">
                            <a href="mailto:{{ $submission->email }}" class="text-indigo-600 hover:underline">{{ $submission->email }}</a>
                            @if ($submission->phone) <span class="text-gray-500">· {{ $submission->phone }}</span> @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Institución y Origen</dt>
                        <dd class="mt-0.5 text-gray-900">
                            {{ $submission->institution ?? '—' }}
                            @if ($submission->city || $submission->country)
                                <span class="text-gray-500">({{ collect([$submission->city, $submission->country])->filter()->implode(', ') }})</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Modalidad a la que aspira</dt>
                        <dd class="mt-0.5 font-semibold text-gray-900">{{ $submission->requestedModalityLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-gray-400">Archivo / Documento del resumen</dt>
                        <dd class="mt-0.5 flex flex-wrap gap-3">
                            @if ($submission->file_path)
                                <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded font-semibold text-xs hover:bg-indigo-100">
                                    Descargar archivo ({{ $submission->file_original_name ?: 'Resumen' }})
                                </a>
                            @endif
                            @if ($submission->file_url)
                                <a href="{{ $submission->file_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded font-semibold text-xs hover:bg-slate-200">
                                    Abrir enlace externo ↗
                                </a>
                            @endif
                            @if (! $submission->file_path && ! $submission->file_url)
                                <span class="text-gray-400">Texto en línea (sin adjunto externo)</span>
                            @endif
                        </dd>
                    </div>

                    @if ($submission->coauthors)
                        <div class="sm:col-span-3">
                            <dt class="text-xs font-semibold uppercase text-gray-400">Coautores</dt>
                            <dd class="mt-0.5 text-gray-800">{{ $submission->coauthors }}</dd>
                        </div>
                    @endif

                    @if ($submission->keywords)
                        <div class="sm:col-span-3">
                            <dt class="text-xs font-semibold uppercase text-gray-400">Palabras clave</dt>
                            <dd class="mt-0.5 text-gray-800">{{ $submission->keywords }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($submission->abstract_text)
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-semibold uppercase text-gray-400 mb-2">Contenido del resumen</h4>
                        <div class="p-4 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $submission->abstract_text }}</div>
                    </div>
                @endif

                @if ($submission->reviewed_at)
                    <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-6 text-xs text-gray-500">
                        <span>Última revisión: <strong>{{ $submission->reviewed_at->format('d/m/Y H:i') }}</strong>@if ($submission->reviewer) por <strong>{{ $submission->reviewer->name }}</strong>@endif</span>
                        @if ($submission->notified_at)
                            <span>Notificado al autor: <strong>{{ $submission->notified_at->format('d/m/Y H:i') }}</strong></span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Dictamen del Comité Científico --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-600">
                <h3 class="font-bold text-lg text-gray-900">Dictamen del Comité Científico</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Define si el resumen es apto y en qué modalidad se aprueba (<strong>Ponencia oral</strong> o <strong>Póster</strong>).
                    Al guardar un dictamen definitivo, el postulante recibe el aviso por correo y al consultar con su cédula podrá descargar la plantilla correspondiente.
                </p>

                <form method="POST" action="{{ route('committee.submissions.review', $submission) }}" class="mt-5 space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <x-input-label for="decision" value="Decisión del comité *" />
                            <select id="decision" name="decision" required
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-medium">
                                <option value="approved" @selected(old('decision', $submission->status) === 'approved')>✓ Aprobar resumen</option>
                                <option value="rejected" @selected(old('decision', $submission->status) === 'rejected')>✕ No aprobar</option>
                                <option value="under_review" @selected(old('decision', $submission->status) === 'under_review')>⏳ Dejar en evaluación</option>
                            </select>
                            <x-input-error :messages="$errors->get('decision')" class="mt-2" />
                        </div>

                        <div id="modalityWrapper">
                            <x-input-label for="modality" value="Modalidad aprobada (si se aprueba) *" />
                            <select id="modality" name="modality"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-medium">
                                <option value="">Selecciona modalidad…</option>
                                <option value="oral" @selected(old('modality', $submission->modality ?? $submission->requested_modality) === 'oral')>
                                    Ponencia oral
                                </option>
                                <option value="poster" @selected(old('modality', $submission->modality ?? $submission->requested_modality) === 'poster')>
                                    Póster (habilita plantilla de póster)
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('modality')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="score" value="Puntaje (0 a 100, opcional)" />
                            <x-text-input id="score" name="score" type="number" min="0" max="100"
                                          class="mt-1 block w-full"
                                          :value="old('score', $submission->score)"
                                          placeholder="Ej. 88" />
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="assigned_to" value="Evaluador asignado del Comité Científico" />
                        <select id="assigned_to" name="assigned_to"
                                class="mt-1 block w-full sm:w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Sin asignar / Evaluación general del comité</option>
                            @foreach ($reviewers as $rev)
                                <option value="{{ $rev->id }}" @selected(old('assigned_to', $submission->assigned_to) == $rev->id)>
                                    {{ $rev->name }} ({{ $rev->roleLabel() }}@if ($rev->specialty) · {{ $rev->specialty }}@endif)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="review_comment" value="Observaciones y retroalimentación para el postulante (visible al consultar con cédula y en el correo)" />
                        <textarea id="review_comment" name="review_comment" rows="4"
                                  placeholder="Escribe aquí las recomendaciones o motivo de la decisión para el autor…"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('review_comment', $submission->review_comment) }}</textarea>
                        <x-input-error :messages="$errors->get('review_comment')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="internal_notes" value="Notas internas del comité (privadas, no visibles para el postulante)" />
                        <textarea id="internal_notes" name="internal_notes" rows="2"
                                  placeholder="Apuntes internos de los evaluadores…"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('internal_notes', $submission->internal_notes) }}</textarea>
                        <x-input-error :messages="$errors->get('internal_notes')" class="mt-2" />
                    </div>

                    <div class="pt-2 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <x-primary-button>Guardar dictamen y notificar</x-primary-button>
                            <a href="{{ route('committee.submissions.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        </div>
                    </div>
                </form>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <form action="{{ route('committee.submissions.destroy', $submission) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar este registro de resumen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar este resumen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
