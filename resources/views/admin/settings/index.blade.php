<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Configuración General, Enlaces y Tarifas · CICI 2027
                </h2>
                <p class="text-sm text-gray-500">
                    RF-01 y RF-06 · Administra la información institucional, los enlaces de formularios/plantillas y los valores de inscripción.
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

            {{-- Parámetros institucionales y enlaces --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-1">Parámetros Institucionales y Enlaces Oficiales</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Si dejas vacío el enlace de <strong>Plantilla de póster</strong> o <strong>Convocatoria</strong>, el sitio público mostrará automáticamente <em>«Próximamente»</em> sin enlaces rotos.
                </p>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($settings as $setting)
                            <div class="{{ in_array($setting->key, ['contact_address', 'abstract_template_url', 'registration_url']) ? 'sm:col-span-2' : '' }}">
                                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1">
                                    {{ $setting->label }} <span class="text-gray-400 font-mono">({{ $setting->key }})</span>
                                </label>
                                <input type="text"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Guardar configuración general
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tarifas de Inscripción (RF-06) --}}
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Agregar Tarifa de Inscripción</h3>
                    <form method="POST" action="{{ route('admin.fees.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perfil / Modalidad *</label>
                            <input type="text" name="profile" required placeholder="Ej: Ponente nacional · oral" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Texto visible *</label>
                                <input type="text" name="price_formatted" required placeholder="$ 350.000" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Valor numérico (COP)</label>
                                <input type="number" name="price_amount" placeholder="350000" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">¿Qué incluye?</label>
                            <input type="text" name="includes" placeholder="Certificado, kit académico, memorias..." class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Añadir tarifa
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900 mb-4">Tarifas Oficiales Publicadas ({{ $fees->count() }})</h3>
                    <div class="space-y-4">
                        @forelse ($fees as $fee)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900">{{ $fee->profile }}</span>
                                        <span class="px-2 py-0.5 text-xs font-bold bg-indigo-50 text-indigo-700 rounded">{{ $fee->price_formatted }} ({{ number_format($fee->price_amount, 0, ',', '.') }} COP)</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $fee->description }}</p>
                                    @if ($fee->includes)
                                        <p class="text-xs text-gray-500 mt-1">Incluye: {{ $fee->includes }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.fees.destroy', $fee) }}" onsubmit="return confirm('¿Eliminar esta tarifa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">Eliminar</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay tarifas registradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
