<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Noticias, Novedades y Avisos · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-08 · Publica comunicados oficiales, aperturas de convocatoria, ampliaciones de fechas y novedades académicas.
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
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Publicar Comunicado o Noticia</h3>
                    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título *</label>
                            <input type="text" name="title" required class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Etiqueta / Categoría *</label>
                            <input type="text" name="category" required value="CICI 2027" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Resumen visible en portada *</label>
                            <textarea name="summary" rows="3" required class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contenido ampliado (opcional)</label>
                            <textarea name="content" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Enlace de acción (opcional)</label>
                            <input type="text" name="external_url" placeholder="https://..." class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Imagen destacada (opcional)</label>
                            <input type="file" name="image" accept="image/*" class="mt-1 w-full text-xs text-gray-600">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_published" value="1" checked class="rounded border-gray-300 text-indigo-600">
                                Publicada
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-indigo-600">
                                Destacada
                            </label>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Publicar noticia
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Noticias Publicadas ({{ $posts->count() }})</h3>
                    <div class="space-y-4">
                        @forelse ($posts as $post)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded">{{ $post->category }}</span>
                                        @if ($post->is_published)
                                            <span class="px-2 py-0.5 text-xs bg-emerald-50 text-emerald-700 rounded">Visible</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Borrador</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $post->published_at?->format('Y-m-d') }}</span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mt-1">{{ $post->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $post->summary }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.news.destroy', $post) }}" onsubmit="return confirm('¿Eliminar esta noticia?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay noticias registradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
