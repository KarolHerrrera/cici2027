<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Comité Organizador, Histórico, Aliados y Preguntas Frecuentes · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-09, RF-10, RF-11 y RF-12 · Gestión integral de las secciones institucionales complementarias del portal público.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Volver al Panel</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- 1. Preguntas Frecuentes FAQ (RF-12) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Preguntas Frecuentes - FAQ ({{ $faqs->count() }}) · RF-12</h3>
                <div class="grid gap-6 lg:grid-cols-3">
                    <form method="POST" action="{{ route('admin.portal.faqs.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Pregunta *</label>
                            <input type="text" name="question" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Categoría *</label>
                            <select name="category" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                <option value="resumenes">Envío y Evaluación de Resúmenes</option>
                                <option value="inscripciones">Inscripción y Pagos</option>
                                <option value="modalidades">Modalidades (Oral y Póster)</option>
                                <option value="certificados">Certificados y Memorias</option>
                                <option value="general">Información General</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Respuesta oficial *</label>
                            <textarea name="answer" rows="3" required class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Agregar pregunta frecuente
                        </button>
                    </form>

                    <div class="lg:col-span-2 space-y-3">
                        @foreach ($faqs as $faq)
                            <div class="border border-gray-200 rounded-lg p-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded">{{ $faq->categoryLabel() }}</span>
                                    <h4 class="font-semibold text-gray-900 text-sm mt-1">{{ $faq->question }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $faq->answer }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.portal.faqs.destroy', $faq) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. Comité Organizador y Científico Público (RF-09) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Comité Directivo, Organizador y Científico Público ({{ $organizers->count() }}) · RF-09</h3>
                <div class="grid gap-6 lg:grid-cols-3">
                    <form method="POST" action="{{ route('admin.portal.organizers.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Nombre o Unidad *</label>
                            <input type="text" name="name" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Rol en el congreso *</label>
                            <input type="text" name="role" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Comité *</label>
                                <select name="committee_type" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                    <option value="directivo">Directivo</option>
                                    <option value="cientifico">Científico</option>
                                    <option value="organizador">Organizador</option>
                                    <option value="logistico">Logístico</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Institución *</label>
                                <input type="text" name="institution" value="Universidad Mariana" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Programa o dependencia</label>
                            <input type="text" name="program_or_unit" placeholder="Ej: Programa de Ingeniería Civil" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Agregar al comité público
                        </button>
                    </form>

                    <div class="lg:col-span-2 grid gap-3 sm:grid-cols-2">
                        @foreach ($organizers as $org)
                            <div class="border border-gray-200 rounded-lg p-3.5 flex flex-col justify-between">
                                <div>
                                    <span class="text-xs font-semibold text-indigo-700">{{ $org->committeeTypeLabel() }}</span>
                                    <h4 class="font-semibold text-gray-900 text-sm mt-0.5">{{ $org->name }}</h4>
                                    <p class="text-xs text-gray-600">{{ $org->role }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $org->institution }} {{ $org->program_or_unit ? '· ' . $org->program_or_unit : '' }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.portal.organizers.destroy', $org) }}" class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3. Aliados, Patrocinadores y Universidades Participantes (RF-11) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Instituciones Aliadas y Patrocinadores ({{ $sponsors->count() }}) · RF-11</h3>
                <div class="grid gap-6 lg:grid-cols-3">
                    <form method="POST" action="{{ route('admin.portal.sponsors.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Institución / Entidad *</label>
                            <input type="text" name="name" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Categoría *</label>
                                <select name="category" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                                    <option value="organizador">Organizador</option>
                                    <option value="aliado_academico">Aliado Académico</option>
                                    <option value="patrocinador">Patrocinador</option>
                                    <option value="apoyo_institucional">Apoyo Institucional</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">País</label>
                                <input type="text" name="country" placeholder="Colombia / México / Ecuador" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Sitio web</label>
                            <input type="url" name="website_url" placeholder="https://..." class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Descripción breve</label>
                            <input type="text" name="description" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Agregar institución aliada
                        </button>
                    </form>

                    <div class="lg:col-span-2 grid gap-3 sm:grid-cols-2">
                        @foreach ($sponsors as $sp)
                            <div class="border border-gray-200 rounded-lg p-3.5 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-semibold text-emerald-700">{{ $sp->categoryLabel() }}</span>
                                        @if ($sp->country)
                                            <span class="text-xs text-gray-500">{{ $sp->country }}</span>
                                        @endif
                                    </div>
                                    <h4 class="font-semibold text-gray-900 text-sm mt-0.5">{{ $sp->name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $sp->description }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.portal.sponsors.destroy', $sp) }}" class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. Galería e Histórico de Ediciones Anteriores (RF-10) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Histórico y Galería de Ediciones Anteriores ({{ $gallery->count() }}) · RF-10</h3>
                <div class="grid gap-6 lg:grid-cols-3">
                    <form method="POST" action="{{ route('admin.portal.gallery.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Edición *</label>
                                <input type="text" name="edition" required placeholder="III CICI" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Año *</label>
                                <input type="number" name="year" required value="2025" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Título *</label>
                            <input type="text" name="title" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Asistentes</label>
                                <input type="text" name="participants_count" placeholder="+800 participantes" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-600">Trabajos</label>
                                <input type="text" name="papers_count" placeholder="+110 ponencias" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600">Reseña histórica</label>
                            <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Agregar edición histórica
                        </button>
                    </form>

                    <div class="lg:col-span-2 space-y-3">
                        @foreach ($gallery as $item)
                            <div class="border border-gray-200 rounded-lg p-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <span class="px-2 py-0.5 text-xs font-bold bg-indigo-50 text-indigo-700 rounded">{{ $item->edition }} ({{ $item->year }})</span>
                                    <h4 class="font-semibold text-gray-900 text-sm mt-1">{{ $item->title }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $item->description }}</p>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">{{ $item->participants_count }} · {{ $item->papers_count }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.portal.gallery.destroy', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
