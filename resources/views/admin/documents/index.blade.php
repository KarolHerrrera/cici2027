<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Documentos Oficiales y Plantillas · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-07 · Sube o enlaza el Formato de Resumen, la Plantilla de Póster, la Convocatoria y el Material de Difusión.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Volver al Panel</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Crear documento --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Registrar Documento o Plantilla</h3>
                    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título del documento *</label>
                            <input type="text" name="title" required placeholder="Ej: Plantilla oficial de Póster" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría *</label>
                                <select name="category" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                    <option value="convocatoria">Convocatoria</option>
                                    <option value="formato_resumen">Formato de Resumen</option>
                                    <option value="plantilla_poster">Plantilla de Póster</option>
                                    <option value="guia_autores">Guía para Autores</option>
                                    <option value="memorias">Memorias</option>
                                    <option value="material_difusion">Material de Difusión</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado *</label>
                                <select name="status" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                    <option value="disponible">Disponible</option>
                                    <option value="proximamente">Próximamente</option>
                                    <option value="oculto">Oculto</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subir archivo (PDF, DOCX, PPTX, ZIP)</label>
                            <input type="file" name="file" class="mt-1 w-full text-xs text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">O enlace externo (Google Docs / Drive)</label>
                            <input type="text" name="external_url" placeholder="https://docs.google.com/..." class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Guardar documento
                        </button>
                    </form>
                </div>

                {{-- Lista y edición rápida de documentos --}}
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Documentos del Congreso ({{ $documents->count() }})</h3>
                    <div class="space-y-4">
                        @forelse ($documents as $doc)
                            <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <span class="font-semibold text-gray-900">{{ $doc->title }}</span>
                                        <span class="text-xs text-gray-400 font-mono ms-1">({{ $doc->slug }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($doc->status === 'disponible')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-full">Disponible</span>
                                        @elseif ($doc->status === 'proximamente')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-amber-50 text-amber-800 rounded-full">Próximamente</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">Oculto</span>
                                        @endif

                                        @if ($doc->downloadUrl())
                                            <a href="{{ $doc->downloadUrl() }}" target="_blank" class="text-xs text-indigo-600 hover:underline font-semibold">Abrir archivo ↗</a>
                                        @endif
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.documents.update', $doc) }}" enctype="multipart/form-data" class="grid gap-3 sm:grid-cols-4 items-end pt-2 border-t border-gray-100">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="title" value="{{ $doc->title }}">
                                    <input type="hidden" name="category" value="{{ $doc->category }}">
                                    <input type="hidden" name="description" value="{{ $doc->description }}">

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs text-gray-500">Enlace externo o ruta</label>
                                        <input type="text" name="external_url" value="{{ $doc->external_url }}" placeholder="https://..." class="mt-0.5 w-full rounded border-gray-300 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Estado</label>
                                        <select name="status" class="mt-0.5 w-full rounded border-gray-300 text-xs">
                                            <option value="disponible" @selected($doc->status === 'disponible')>Disponible</option>
                                            <option value="proximamente" @selected($doc->status === 'proximamente')>Próximamente</option>
                                            <option value="oculto" @selected($doc->status === 'oculto')>Oculto</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded text-xs font-semibold hover:bg-slate-700">
                                            Actualizar
                                        </button>
                                    </div>
                                    <div class="sm:col-span-4">
                                        <label class="block text-xs text-gray-500">Subir nuevo archivo (opcional, habilita descarga directa):</label>
                                        <input type="file" name="file" class="mt-0.5 text-xs text-gray-600">
                                    </div>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay documentos registrados.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
