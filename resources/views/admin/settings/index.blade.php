<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Configuración General, Identidad Visual, Enlaces y Tarifas
                </h2>
                <p class="text-sm text-gray-500">
                    RF-01 y RF-06 · Administra el año de la edición (ej. 2027 → 2028), los logos oficiales, enlaces y valores de inscripción.
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

            {{-- Parámetros institucionales, Logos y Cambio de Edición (2027 -> 2028) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">Parámetros Institucionales, Edición y Logos Oficiales</h3>
                        <p class="text-sm text-gray-500">
                            Desde aquí puedes cambiar toda la plataforma de <strong>2027 a 2028</strong> (año, edición, nombre, fechas y logos) sin tocar código.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="fillEditionPreset('2028')"
                                class="px-3 py-1.5 bg-amber-50 border border-amber-300 text-amber-800 rounded-md text-xs font-semibold hover:bg-amber-100">
                            ⚡ Autocompletar ejemplo CICI 2028 (V Edición)
                        </button>
                        <button type="button" onclick="fillEditionPreset('2027')"
                                class="px-3 py-1.5 bg-slate-100 border border-slate-300 text-slate-700 rounded-md text-xs font-semibold hover:bg-slate-200">
                            ↺ Restaurar datos oficiales CICI 2027 (IV Edición)
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Subida de Logos Institucionales --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-700 mb-1">
                                Subir nuevo Logo Principal Blanco (Banner Hero y Pie de página)
                            </label>
                            <input type="file" name="logo_white_file" accept=".png,.jpg,.jpeg,.webp,.svg"
                                   class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500 mt-1">
                                Opcional (PNG/SVG transparente). Si cambias el año a <strong>2028</strong> sin subir archivo, el banner adapta el logo automáticamente con el nuevo año.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-700 mb-1">
                                Subir nuevo Logo Sigla (Barra de navegación superior e ícono)
                            </label>
                            <input type="file" name="logo_sigla_file" accept=".png,.jpg,.jpeg,.webp,.svg"
                                   class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <label class="inline-flex items-center gap-2 mt-2 text-xs text-gray-600 cursor-pointer">
                                <input type="checkbox" name="reset_logos" value="1" class="rounded border-gray-300 text-indigo-600">
                                Restaurar los logos originales por defecto
                            </label>
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($settings as $setting)
                            <div class="{{ in_array($setting->key, ['contact_address', 'abstract_template_url', 'registration_url']) ? 'sm:col-span-2' : '' }}">
                                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1">
                                    {{ $setting->label }} <span class="text-gray-400 font-mono">({{ $setting->key }})</span>
                                </label>
                                <input type="text"
                                       id="setting_{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700">
                            Guardar configuración general y logos
                        </button>
                    </div>
                </form>
            </div>

            <script>
                function fillEditionPreset(year) {
                    const presets = {
                        '2028': {
                            event_name: 'V Congreso Internacional de Ciencia e Ingeniería',
                            event_year: '2028',
                            event_edition_badge: 'V edición · 2028',
                            event_dates_label: '12, 13 y 14 de abril de 2028',
                            event_start_iso: '2028-04-12T00:00:00-05:00'
                        },
                        '2027': {
                            event_name: 'IV Congreso Internacional de Ciencia e Ingeniería',
                            event_year: '2027',
                            event_edition_badge: 'IV edición · 2027',
                            event_dates_label: '14, 15 y 16 de abril de 2027',
                            event_start_iso: '2027-04-14T00:00:00-05:00'
                        }
                    };
                    const data = presets[year];
                    if (!data) return;
                    Object.keys(data).forEach(key => {
                        const input = document.getElementById('setting_' + key);
                        if (input) input.value = data[key];
                    });
                }
            </script>

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
